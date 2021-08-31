<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\models\Orders;
use app\models\Products;
use app\models\ProductLines;
use app\models\Customers;
use app\core\Request;
use Swoole\Http\Response;

class ProductsController extends Controller
{

  public function getProducts(Request $request, Response $response)
  {
    $products = new Products();

    $this->setLayout('main');
    
    /*
    if( !Application::$app->session->get('products') )
    {
      Application::$app->session->set('products', $products->fetchAllRecords() );
    }
    */

    $allProducts = $products->fetchAllRecords();
    
    //$count = count(Application::$app->session->get('products') );
    $count = count($allProducts );
    
    $response->header('Content-Type', 'text/html');
    $response->setStatusCode(200);
    return $response->end( $this->render('products', [
                                      'allProducts' => $allProducts,
                                      'count' => $count, ]) );
  }

  
  public function getProduct(Request $request, Response $response)
  {
    $products = new Products();

    $this->setLayout('main');
    
    $prodID = $request->getProductPath();
    
    $response->header('Content-Type', 'text/html');
    $response->setStatusCode(200);
    return $response->end( $this->render('product_template', ['product' => $products->getProduct($prodID) ]) );
  }
  
  
  public function createProductForm(Request $request, Response $response)
  {
    $products = new Products();
    
    $lines = new ProductLines();

    $this->setLayout('main');

    $response->header('Content-Type', 'text/html');
    $response->setStatusCode(200);
    return $response->end( $this->render('createProduct', [
                                           'productScales' => $products->fetchProductScales(),
                                           'productLines' => $lines->fetchProductLines(),
                                          ]) );
  }
  
  //works on POST event
  public function createProduct(Request $request, Response $response)
  {
    $product = new Products();
    
    $message = $product->insertRecord( $request->getRequestBody() );
    
    $response->setStatusCode(200);
    
    //update the products in session with new product
    //Application::$app->session->set('products', $product->fetchAllRecords() );
    
    $response->redirect('/products/addProduct');
  }
    
  
  public function fetchAllNames(Request $request, Response $response)
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
    $response->header('Content-Type', 'application/json');
    $response->setStatusCode(200);
    return $response->end( json_encode($allProds) );
  }

  
  public function editProductForm(Request $request, Response $response)
  {
    $products = new Products();
    
    $lines = new ProductLines();

    $this->setLayout('main');
  
    $prodID = $request->getProductPath();

    $response->header('Content-Type', 'text/html');
    $response->setStatusCode(200);
    return $response->end( $this->render('editProduct', [
                                           'product' => $products->getProduct($prodID),
                                           'productScales' => $products->fetchProductScales(),
                                           'productLines' => $lines->fetchProductLines(),
                                          ]) );
  }
  
  
  public function editProduct(Request $request, Response $response)
  {
    $product = new Products();
    
    $prodID = $request->getProductPath();
    
    //$product->loadData( $request->getRequestBody() );

    $message = $product->updateRecord( $request->getRequestBody(), $prodID);

    //update the products in session with updated product
    //Application::$app->session->set('products', $product->fetchAllRecords() );
    $response->header('Content-Type', 'application/json');
    $response->setStatusCode(200);
    return $response->end( json_encode(['message' => $message]) );
  }

  
  public function serverPush(Request $request, Response $response)
  {
    $response->header("Content-Type", "text/event-stream");
    $response->header("Access-Control-Allow-Origin", "*");
    $response->header("Cache-Control", "no-cache");
    
    $statement = Application::$app->db->prepare("SELECT productCode, productName, productLine, productScale, productVendor, productDescription, quantityInStock, buyPrice, MSRP, action FROM products_audit WHERE auditTime + interval 20 second > CURRENT_TIMESTAMP();");
    
    while(1)
    {
      $data = "event: ping\n";
      
      $response->write($data);
    
      $statement->execute();
    
      $data2 = json_encode( $statement->fetchAll(\PDO::FETCH_ASSOC) );

      if($data2 !== '')
      {
        $result = "event: message\n" .
                  "data: $data2". "\n\n";
                    
        $response->write($result);
        
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
