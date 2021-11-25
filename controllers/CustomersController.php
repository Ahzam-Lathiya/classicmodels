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
use app\core\exceptions\ForbiddenException;

class CustomersController extends Controller
{
  
  public function getCustomers(Request $request, Response $response)
  {
    if( Application::isGuest() )
    {
      throw new ForbiddenException;
    }
  
    $customer = new Customers();

    $this->setLayout('main');
    
    
    if( !Application::$app->session->get('customers') )
    {
      Application::$app->session->set('customers', $customer->fetchAllrecords() );
    }
    
    $response->setStatusCode(200);
    $response->header('Content-Type', 'text/html');
    return $response->end( $this->render('customers', 
                           ['allCustomers' => Application::$app->session->get('customers') ] 
                         ) );
  }
  
  
  public function getCustomer(Request $request, Response $response)
  {
    $customer = new Customers();
    
    $response->header('Content-Type', 'application/json');
    $response->setStatusCode(200);
    return $response->end( json_encode( $customer->fetchCustomer( $request->getOrderPath() ) ) );
  }
  
}

?>
