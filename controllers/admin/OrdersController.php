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

class OrdersController extends Controller
{
  
  public function filterByStatus()
  {
    $body = $this->request->getRequestBody();
  
    $allOrders = Application::$app->session->get('orders');
    
    if( $body['choice'] === 'all' )
    {
      $this->response->header('Content-Type', 'application/json');
      $this->response->setStatusCode(200);
      
      return $this->response->end( json_encode($allOrders) );
    }
    
    $selected = [];
    
    foreach($allOrders as $order)
    {
      if( $body['choice'] === $order['status'] )
      {
        $selected[] = $order;
      }

    }

    $this->response->header('Content-Type', 'application/json');
    $this->response->setStatusCode(200);
    
    return $this->response->end( json_encode($selected) );
  }
  
  
  public function getOrder()
  {
    $order = new Orders();

    $this->setLayout('main');
  
    $orderID = $this->request->getOrderPath();
    
    $this->response->header('Content-Type', 'text/html');
    $this->response->setStatusCode(200);
    return $this->response->end( $this->render('order_template', [
                                            'orderID' => $orderID,
                                            'orderDetails' => $order->fetchOrderView($orderID),
                                            'order' => $order->fetchSingleOrder($orderID),
                                           ]) );
  }


  public function getOrders()
  {
    //if the user is not logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }
    
    $orders = new Orders();

    $this->setLayout('main');
    
    $allOrd = [];
    
    if( !Application::$app->session->get('orders') )
    {
      $allOrd = $orders->fetchAllRecords();
      Application::$app->session->set('orders',  $allOrd);
    }
    
    else
    {
      $allOrd = Application::$app->session->get('orders');
    }
    
    //$allOrd = $orders->fetchAllRecords();
    
    //echo $allOrd . PHP_EOL;
    
    $count = count($allOrd );
    //$count = count($allOrd);
    
    $this->response->header('Content-Type', 'text/html');
    $this->response->setStatusCode(200);
    return $this->response->end( $this->render('order', [
                                   'allOrders' => $allOrd,
                                   'statusTypes' => $orders->getStatusTypes(),
                                   'count'     => $count, ]) );
  }
  
}

?>
