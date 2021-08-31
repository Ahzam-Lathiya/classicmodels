<?php

namespace app\controllers;

use app\models\Employees;
use app\models\Offices;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use Swoole\Http\Response;
use app\core\exceptions\ForbiddenException;

class ProfileController extends controller
{

  public function Login(Request $request, Response $response)
  {
    $this->setLayout('auth');

    $response->setStatusCode(200);
    $response->header('Content-Type', 'text/html');
    return $response->end($this->render('login') );
  }


  public function showProfile(Request $request, Response $response)
  {
    //if the user is not logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }

    $this->setLayout('main');

    $empID = Application::$app->session->get('user');
    
    $employee = Application::$app->user;
    
    return $this->render('profile', ['employee' => $employee] );
  }

  
  public function editPassword(Request $request)
  {
    $body = $request->getRequestBody();

    if($body['password'] === $body['passwordChange'])
    {
      $employee = Application::$app->user;
      $password = password_hash($body['passwordChange'], PASSWORD_DEFAULT);
      
      $employee->updateAttribute( 'password', $password );
    }
    
    return $this->render('profile', ['message' => "Password Changed Successfully"] );
  }

  
  public function registerPage()
  {
    //check if the user is logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }

    $this->setLayout('main');
  
    //if managers aren't retrieved in a session
    if( !Application::$app->session->get('managers') )
    {
      $employee = Application::$app->user;
      
      //set managers in session so that they are not retrieved from DB for every call
      Application::$app->session->set('managers', $employee->getManagers() );
    }
    
    //if offices aren't retrieved in a session
    if( !Application::$app->session->get('offices') )
    {
      $office = new Offices();
      
      //set offices in session so that they are not retrieved from DB for every call
      Application::$app->session->set('offices', $office->getCities() );
    }
  
    return $this->render('register', [
                                      'offices' => Application::$app->session->get('offices'),
                                      'managers' => Application::$app->session->get('managers')
                                     ]);
  }
  
  
  public function createUser(Request $request)
  {
    $employee = Application::$app->user;
    
    //$employee->loadData( $request->getRequestBody() );
    
    return $employee->insertRecord( $request->getRequestBody() );
    
    //return "Wow Grape!!";
    //header("Location:/");
    //return json_encode($_POST);
  }

}

?>
