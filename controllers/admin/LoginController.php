<?php

namespace app\controllers\admin;

use app\models\Employees;
use app\core\Controller;
use Swoole\Http\Response;
use app\core\Request;
use app\core\Application;
use app\core\exceptions\ForbiddenException;

class LoginController extends controller
{

  public function login(Request $request, Response $response)
  {
    $this->setLayout('auth');

    $response->setStatusCode(200);
    $response->header('Content-Type', 'text/html');
    return $response->end( $this->render('login') );
  }

  //POST request
  public function handleLoginform(Request $request, Response $response)
  {
    $this->setLayout('auth');

    $employee = new Employees();

    $body = $request->getRequestBody();

    // check if user ID already exists in session
    if( Application::$app->session->userExists( $body['emp_ID' ]) !== [] )
    {
      throw new ForbiddenException();
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

    $response->redirect("/admin");
  }
}

?>
