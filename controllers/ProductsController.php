<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\models\Orders;
use app\models\Products;
use app\models\ProductLines;
use app\models\Customers;
use app\core\Request;
use app\core\Response;

class ProductsController extends Controller
{

  public function getProducts()
  {
    $products = new Products();

    $this->setLayout('main');
    
    if( !Application::$app->session->get('products') )
    {
      Application::$app->session->set('products', $products->fetchAllRecords() );
    }
    
    $count = count(Application::$app->session->get('products') );
    
    return $this->render('products', [
                                      'allProducts' => Application::$app->session->get('products'),
                                      'count' => $count, ]);
  }

  
  public function getProduct(Request $request, Response $response)
  {
    $products = new Products();

    $this->setLayout('main');
    
    $prodID = $request->getProductPath();
    
    return $this->render('product_template', ['product' => $products->getProduct($prodID) ]);
  }
  
  
  public function createProductForm()
  {
    $products = new Products();
    
    $lines = new ProductLines();

    $this->setLayout('main');
    
    return $this->render('createProduct', [
                                           'productScales' => $products->fetchProductScales(),
                                           'productLines' => $lines->fetchProductLines(),
                                          ]);
  }
  
  //works on POST event
  public function createProduct(Request $request, Response $response)
  {
    $product = new Products();
    
    $message = $product->insertRecord( $request->getRequestBody() );
    
    $response->setStatusCode(200);
    
    //update the products in session with new product
    Application::$app->session->set('products', $product->fetchAllRecords() );
    
    return $response->redirect('/products/addProduct');
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
    
    return json_encode($allProds);
  }

  
  public function editProductForm(Request $request)
  {
    $products = new Products();
    
    $lines = new ProductLines();

    $this->setLayout('main');
  
    $prodID = $request->getProductPath();
    
    return $this->render('editProduct', [
                                           'product' => $products->getProduct($prodID),
                                           'productScales' => $products->fetchProductScales(),
                                           'productLines' => $lines->fetchProductLines(),
                                          ]);
  }
  
  
  public function editProduct(Request $request)
  {
    $product = new Products();
    
    $prodID = $request->getProductPath();
    
    //$product->loadData( $request->getRequestBody() );

    $message = $product->updateRecord( $request->getRequestBody(), $prodID);

    //update the products in session with updated product
    Application::$app->session->set('products', $product->fetchAllRecords() );
    
    return json_encode(['message' => $message]);
    
    //trigger the push event. Send updated record of the product to the products view

  }

  
  public function serverPush()
  {
    header('Content-Type: text/event-stream');
    header("Cache-Control: no-cache");
    
    /*
    $counter = rand(1, 10); // a random counter
    
      // 1 is always true, so repeat the while loop forever (aka event-loop)

      $curDate = date(DATE_ISO8601);
      echo "event: ping\n",
           "data: King $counter", "\n\n";

      // Send a simple message at random intervals.

      $counter--;

      if (!$counter)
      {
        echo 'data: This is a message at time ' . $curDate, "\n\n";
        $counter = rand(1, 10); // reset random counter
      }

      // flush the output buffer and send echoed messages to the browser

      while (ob_get_level() > 0)
      {
        ob_end_flush();
      }
      
      flush();

      // break the loop if the client aborted the connection (closed the page)
  
      if ( connection_aborted() )
      {
        break;
      }

      // sleep for 1 second before running the loop again
      sleep(1);
      */
      
      while(1)
      {
        $products = new Products();
        
        $data = json_encode( $products->fetchProductAudits() );
        
        if($data !== '')
        {
          /*
          echo "event: ping\n",
               "data: $data", "\n\n";
          */

          echo "event: message\n",
               "data: $data", "\n\n";
        
          while (ob_get_level() > 0)
          {
            ob_end_flush();
          }

          flush();
        }
        
        if( connection_aborted() )
        {
          return;
        }
      
        sleep(50);
      }
  }
  
}

?>
