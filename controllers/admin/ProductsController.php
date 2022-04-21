<?php

namespace app\controllers\admin;

use app\core\Controller;
use app\core\Application;
use app\models\Orders;
use app\models\Products;
use app\models\ProductLines;
use app\models\Customers;
use app\core\Request;
use app\core\exceptions\ForbiddenException;
use Swoole\Http\Response;

class ProductsController extends Controller
{

  public function getProducts()
  {
    if( Application::isGuest() )
    {
      throw new ForbiddenException;
    }
    
    $this->setLayout('main');
    
    $allParams = Application::$app->request->getURLParams();
    
    $page = $allParams['page'];
    
    $products = new Products();
    
    if( !Application::$app->session->get('products' . $page) )
    {
      Application::$app->session->set('products' . $page, $products->fetchPaginatedRecords($page) );
    }
    
    $count = count(Application::$app->session->get('products' . $page) );
    
    $this->response->header('Content-Type', 'text/html');
    $this->response->setStatusCode(200);
    return $this->response->end( $this->render('products', [
                                      'allProducts' => Application::$app->session->get('products' . $page),
                                      'count' => $count ]) );
    
  }

  
  public function getProduct()
  {
    $products = new Products();

    $this->setLayout('main');
    
    $prodID = $this->request->getProductPath();
    
    $this->response->header('Content-Type', 'text/html');
    $this->response->setStatusCode(200);
    return $this->response->end( $this->render('product_template', ['product' => $products->getProduct($prodID) ]) );
  }
  
  
  public function createProductForm()
  {
    $products = new Products();
    
    $lines = new ProductLines();

    $this->setLayout('main');

    $this->response->header('Content-Type', 'text/html');
    $this->response->setStatusCode(200);
    return $this->response->end( $this->render('createProduct', [
                                           'productScales' => $products->fetchProductScales(),
                                           'productLines' => $lines->fetchProductLines(),
                                          ]) );
  }
  
  //works on POST event
  public function createProduct()
  {
    $product = new Products();
    
    $message = $product->insertRecord( $this->request->getRequestBody() );
    
    $this->response->setStatusCode(200);
    
    //update the products in session with new product
    Application::$app->session->update('products', $product->fetchAllRecords() );
    
    $this->response->redirect('/products/addProduct');
  }
  
  
  public function fetchAllNames()
  {
    $products = new Products();
    
    $selected = [];
    
    //return json_encode($products->getProductNames() );
    if( !Application::$app->session->get('products') )
    {
      Application::$app->session->set('products', $products->fetchAllRecords() );
    }
    
    $allProds = Application::$app->session->get('products');
    
    /*
    foreach($allProds as $product)
    {
      $selected[] = $product['productName'];
    }
    
    //$result = $this->mergeSort($selected);
    */
    $this->response->header('Content-Type', 'application/json');
    $this->response->setStatusCode(200);
    return $this->response->end( json_encode($allProds) );
  }

  
  public function editProductForm()
  {
    $products = new Products();
    
    $lines = new ProductLines();

    $this->setLayout('main');
  
    $prodID = $this->request->getProductPath();

    $this->response->header('Content-Type', 'text/html');
    $this->response->setStatusCode(200);
    return $this->response->end( $this->render('editProduct', [
                                           'product' => $products->getProduct($prodID),
                                           'productScales' => $products->fetchProductScales(),
                                           'productLines' => $lines->fetchProductLines(),
                                          ]) );
  }
  
  
  public function editProduct()
  {
    $product = new Products();
    
    $prodID = $this->request->getProductPath();
    
    //$product->loadData( $this->request->getRequestBody() );

    $message = $product->updateRecord( $this->request->getRequestBody(), $prodID);

    //update the products in session with updated product
    //Application::$app->session->set('products', $product->fetchAllRecords() );
    $this->response->header('Content-Type', 'application/json');
    $this->response->setStatusCode(200);
    return $this->response->end( json_encode(['message' => $message]) );
  }

  
  public function serverPush()
  {
    $this->response->header("Content-Type", "text/event-stream");
    $this->response->header("Access-Control-Allow-Origin", "*");
    $this->response->header("Cache-Control", "no-cache");
    
    $statement = Application::$app->db->prepare("SELECT productCode, productName, productLine, productScale, productVendor, productDescription, quantityInStock, buyPrice, MSRP, action FROM products_audit WHERE auditTime + interval 20 second > CURRENT_TIMESTAMP();");
    
    while(1)
    {
      $data = "event: ping\n";
      
      $this->response->write($data);
    
      $statement->execute();
    
      $data2 = json_encode( $statement->fetchAll(\PDO::FETCH_ASSOC) );

      if($data2 !== '')
      {
        $result = "event: message\n" .
                  "data: $data2". "\n\n";
                    
        $this->response->write($result);
        
      }
      
      if( connection_aborted() )
      {
        return;
      }
  
      sleep(20);
    }
  }
  
}

?>
