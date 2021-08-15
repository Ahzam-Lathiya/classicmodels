<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\core\exceptions\NotFoundException;
use app\core\Response;
use app\core\Request;

class SecretController extends controller
{
  
  public function accessSecret1(Request $request, Response $response)
  {
    $this->setLayout('main');

    //if user is logged in i.e he is not guest
    if( !Application::isGuest() )
    {
      return $this->render('secret1');
    }
    
    else
    {
      throw new NotFoundException();
    }
  }
}

?>
