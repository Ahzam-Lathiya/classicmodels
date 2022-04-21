<?php

namespace app\controllers\admin;

use app\models\Employees;
use app\core\Controller;
use Swoole\Http\Response;
use app\core\Request;
use app\core\Application;
use app\core\exceptions\ForbiddenException;
use app\core\exceptions\JsonException;

class LoginController extends controller
{

  public function login()
  {
    $this->setLayout('auth');

    $this->response->setStatusCode(200);
    $this->response->header('Content-Type', 'text/html');
    return $this->response->end( $this->render('login') );
  }

  //POST request
  public function handleLoginform()
  {
    $this->setLayout('auth');

    $employee = new Employees();

    $body = $this->request->getRequestBody();

    // check if user ID already exists in session
    if( Application::$app->session->userExists( $body['emp_ID' ]) !== [] )
    {
      throw new JsonException();
    }
    
    
    if( $employee->verifyID( $body['emp_ID'] ) )
    {
      //if user exists
      
      if( $employee->verifyPass( $body['emp_ID'], $body['password'] ) )
      {
        //if user as well as the password exists
        $empData = $employee->getRow( $body['emp_ID'] );
        
        $employee->loadData($empData);
        
        if(Application::$app->login($employee))
        {
          $this->response->setStatusCode(200);
          $this->response->header('Content-Type', 'Application/json');
          return $this->response->end( json_encode(['message' => 'Successfully Logged In.']) );
        }
        
        //$this->response->redirect("/");
      }
      
      else
      {
        $this->response->setStatusCode(401);
        $this->response->header('Content-Type', 'Application/json');
        return $this->response->end( json_encode(['message' => 'Incorrect Password for User ID']) );
      }
      
    }
    
    else
    {
      $this->response->setStatusCode(404);
      $this->response->header('Content-Type', 'Application/json');
      return $this->response->end( json_encode(['message' => 'User ID does not exist']) );
    }

  }
  
  public function handleLogout()
  {
    Application::$app->logout();

    $this->response->redirect("/admin");
  }
}

?>
