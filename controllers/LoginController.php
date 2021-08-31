<?php

namespace app\controllers;

use app\models\Employees;
use app\core\Controller;
use Swoole\Http\Response;
use app\core\Request;
use app\core\Application;

class LoginController extends controller
{

  public function Login(Request $request, Response $response)
  {
    $this->setLayout('auth');

    $response->setStatusCode(200);
    $response->header('Content-Type', 'text/html');
    return $response->end( $this->render('login') );
  }

  
  public function handleLoginform(Request $request, Response $response)
  {
    $employee = new Employees();

    $this->setLayout('auth');

    $body = $request->getRequestBody();
    
    if( $employee->verifyID( $body['emp_ID'] ) )
    {
      if( $employee->verifyPass( $body['emp_ID'], $body['password'] ) )
      {
        $empData = $employee->getRow( $body['emp_ID'] );
        
        $employee->loadData($empData);
        
        if(Application::$app->login($employee))
        {
          $response->setStatusCode(200);
          $response->header('Content-Type', 'Application/json');
          return $response->end( json_encode(['message' => 'Successfully Logged In.']) );
        }
        
        //$response->redirect("/");
      }
      
      else
      {
        $response->setStatusCode(401);
        $response->header('Content-Type', 'Application/json');
        return $response->end( json_encode(['message' => 'Incorrect Password for User ID']) );
      }
      
    }
    
    else
    {
      $response->setStatusCode(404);
      $response->header('Content-Type', 'Application/json');
      return $response->end( json_encode(['message' => 'User ID does not exist']) );
    }

  }
  
  public function handleLogout(Request $request, Response $response)
  {
    Application::$app->logout();

    $response->redirect("/");
  }
}

?>
