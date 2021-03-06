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
use Swoole\Coroutine;

class SiteController extends Controller
{

  public function home()
  {
    $this->setLayout('main');
    
    //return $this->render('home');
    //return 'Greetings From Home Page';
    $this->response->header('Content-Type', 'text/html');
    return $this->response->end( $this->render('home') );
  }
  
  
  public function about()
  {
    $this->setLayout('main');

    //return $this->render('about');
    //return 'Greetings From About Page';
    $this->response->header('Content-Type', 'text/html');
    return $this->response->end( $this->render('about') );
  }
  
  public function allSessions()
  {
    $answer = Application::$app->session->userExists('1088');
  
    $this->response->header('Content-Type', 'application/json');
    return $this->response->end( json_encode($answer) );
  }


  public function stuck()
  {
    $this->response->header("Content-Type", "text/event-stream");
    $this->response->header("Access-Control-Allow-Origin", "*");
    $this->response->header("Cache-Control", "no-cache");
    
    $statement = Application::$app->db->prepare("SELECT productCode, productName, productLine, productScale, productVendor, productDescription, quantityInStock, buyPrice, MSRP, action FROM products_audit WHERE auditTime + interval 20 second > CURRENT_TIMESTAMP();");
    
    while(1)
    {
      $data = "event: ping\n";
      
      $this->response->write($data);
    
      $statement->execute();
    
      $data2 = json_encode( $statement->fetchAll(\PDO::FETCH_ASSOC) );

      if($data2 !== '')
      {
        $result = "event: message\n" .
                  "data: $data2". "\n\n";
                    
        $this->response->write($result);
        
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
