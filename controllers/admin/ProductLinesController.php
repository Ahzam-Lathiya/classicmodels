<?php

namespace app\controllers\admin;

use app\core\Controller;
use app\core\Application;
use app\models\Orders;
use app\models\Products;
use app\models\ProductLines;
use app\models\Customers;
use app\core\Request;
use Swoole\Http\Response;
use app\core\exceptions\ForbiddenException;

class ProductLinesController extends Controller
{
  //GET
  public function getProductlines()
  {
    //if the user is not logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }
  
    $lines = new ProductLines();

    $this->setLayout('main');
    
    $this->response->setStatusCode(200);
    $this->response->header('Content-Type', 'text/html');
    return $this->response->end( $this->render('productLines', ['productLines' => $lines ] ) );
  }
  
  //GET
  public function productLineForm()
  {
    //if the user is not logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }

    $lines = new ProductLines();

    $this->setLayout('main');
    
    $this->response->setStatusCode(200);
    $this->response->header('Content-Type', 'text/html');
    return $this->response->end( $this->render('createProductLine') );
  }
  
  
  //POST
  public function createProductLine()
  {
    $lines = new ProductLines();
    
    $message = $lines->insertRecord( $this->request->getRequestBody() );
    
    if($message)
    {
      $this->response->setStatusCode(200);
      $this->response->header('Content-Type', 'Application/json');
      return $this->response->end( json_encode(['message' => $message]) );
    }
    
    //return $this->response->redirect('/productLines/addProductLine');
  }
  
}

?>
