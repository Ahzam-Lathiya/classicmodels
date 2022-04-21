<?php

namespace app\controllers\admin;

use app\core\Controller;
use app\core\Application;
use app\core\exceptions\ForbiddenException;
use Swoole\Http\Response;
use app\core\Request;

class SecretController extends controller
{
  public function accessSecret1()
  {
    $this->setLayout('main');

    //if user is logged in i.e he is not guest
    if( !Application::isGuest() )
    {
      $this->response->header('Content-Type', 'text/html');
      $this->response->setStatusCode(200);
      return $this->response->end( $this->render('secret1') );
    }
    
    else
    {
      throw new ForbiddenException();
    }
  }
}

?>
