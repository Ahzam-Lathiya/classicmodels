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

class CustomersController extends Controller
{
  
  public function getCustomers()
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
    
    $this->response->setStatusCode(200);
    $this->response->header('Content-Type', 'text/html');
    return $this->response->end( $this->render('customers', 
                           ['allCustomers' => Application::$app->session->get('customers') ] 
                         ) );
  }
  
  
  public function getCustomer()
  {
    $customer = new Customers();
    
    $this->response->header('Content-Type', 'application/json');
    $this->response->setStatusCode(200);
    return $this->response->end( json_encode( $customer->fetchCustomer( $this->request->getOrderPath() ) ) );
  }
  
}

?>
