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

class OrdersController extends Controller
{
  
  public function filterByStatus(Request $request, Response $response)
  {
    $body = $request->getRequestBody();
  
    $allOrders = Application::$app->session->get('orders');
    
    if( $body['choice'] === 'all' )
    {
      return json_encode($allOrders);
    }
    
    $selected = [];
    
    foreach($allOrders as $order)
    {
      if( $body['choice'] === $order['status'] )
      {
        $selected[] = $order;
      }

    }
    
    return json_encode($selected);
  }
  
  
  public function getOrder(Request $request, Response $response)
  {
    $order = new Orders();

    $this->setLayout('main');
  
    $orderID = $request->getOrderPath();
    
    $response->header('Content-Type', 'text/html');
    $response->setStatusCode(200);
    return $response->end( $this->render('order_template', [
                                            'orderID' => $orderID,
                                            'orderDetails' => $order->fetchOrderView($orderID),
                                            'order' => $order->fetchSingleOrder($orderID),
                                           ]) );
  }


  public function getOrders(Request $request, Response $response)
  {
    $orders = new Orders();

    $this->setLayout('main');
    
    /*
    if( !Application::$app->session->get('orders') )
    {
      Application::$app->session->set('orders', $orders->fetchAllRecords() );
    }
    */
    $allOrd = $orders->fetchAllRecords();
    
    //$count = count(Application::$app->session->get('orders') );
    $count = count($allOrd);
    
    $response->header('Content-Type', 'text/html');
    $response->setStatusCode(200);
    return $response->end( $this->render('order', [
                                   'allOrders' => $allOrd,
                                   'statusTypes' => $orders->getStatusTypes(),
                                   'count'     => $count, ]) );
  }
  
}

?>
