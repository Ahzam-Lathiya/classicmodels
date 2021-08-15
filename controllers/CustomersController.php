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

class CustomersController extends Controller
{
  
  public function getCustomers()
  {
    $customer = new Customers();

    $this->setLayout('main');
    
    if( !Application::$app->session->get('customers') )
    {
      Application::$app->session->set('customers', $customer->fetchAllrecords() );
    }
    
    return $this->render('customers', ['allCustomers' => Application::$app->session->get('customers') ] );
  }
  
  
  public function getCustomer(Request $request)
  {
    $customer = new Customers();
    
    return json_encode( $customer->fetchCustomer( $request->getOrderPath() ) );
  }
  
}

?>
