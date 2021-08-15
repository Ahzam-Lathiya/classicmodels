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

class ProductLinesController extends Controller
{
  
  public function getProductlines()
  {
    $lines = new ProductLines();

    $this->setLayout('main');
    
    return $this->render('productLines', ['productLines' => $lines ] );
  }
  
  
  public function productLineForm()
  {
    $lines = new ProductLines();

    $this->setLayout('main');
    
    return $this->render('createProductLine');
  }
  
  
  public function createProductLine(Request $request, Response $response)
  {
    $lines = new ProductLines();
    
    $message = $lines->insertRecord( $request->getRequestBody() );
    
    if($message)
    {
      $response->setStatusCode(200);
      return json_encode(['message' => $message]);
    }
    
    //return $response->redirect('/productLines/addProductLine');
  }
  
}

?>
