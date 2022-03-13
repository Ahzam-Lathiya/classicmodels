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
  public function getProductlines(Request $request, Response $response)
  {
    //if the user is not logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }
  
    $lines = new ProductLines();

    $this->setLayout('main');
    
    $response->setStatusCode(200);
    $response->header('Content-Type', 'text/html');
    return $response->end( $this->render('productLines', ['productLines' => $lines ] ) );
  }
  
  //GET
  public function productLineForm(Request $request, Response $response)
  {
    //if the user is not logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }

    $lines = new ProductLines();

    $this->setLayout('main');
    
    $response->setStatusCode(200);
    $response->header('Content-Type', 'text/html');
    return $response->end( $this->render('createProductLine') );
  }
  
  
  //POST
  public function createProductLine(Request $request, Response $response)
  {
    $lines = new ProductLines();
    
    $message = $lines->insertRecord( $request->getRequestBody() );
    
    if($message)
    {
      $response->setStatusCode(200);
      $response->header('Content-Type', 'Application/json');
      return $response->end( json_encode(['message' => $message]) );
    }
    
    //return $response->redirect('/productLines/addProductLine');
  }
  
}

?>
