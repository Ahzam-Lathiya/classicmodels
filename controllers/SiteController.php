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

class SiteController extends Controller
{

  public function home()
  {
    $this->setLayout('main');
    
    return $this->render('home');
  }
  
  
  public function about()
  {
    $this->setLayout('main');

    return $this->render('about');
  }
  
}

?>
