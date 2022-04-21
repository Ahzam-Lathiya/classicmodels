<?php

namespace app\controllers\admin;

use app\models\Employees;
use app\models\Offices;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use Swoole\Http\Response;
use app\core\exceptions\ForbiddenException;

class ProfileController extends controller
{

  public function Login()
  {
    $this->setLayout('auth');

    $this->response->setStatusCode(200);
    $this->response->header('Content-Type', 'text/html');
    return $this->response->end($this->render('login') );
  }


  public function showProfile()
  {
    //if the user is not logged in
    if( Application::isGuest() )
    {
      throw new ForbiddenException();
    }

    $this->setLayout('main');

    $empID = Application::$app->session->get('user');
    
    $employee = Application::$app->user;
    
    return $this->response->end( $this->render('profile', ['employee' => $employee] ) );
  }

  
  public function editPassword()
  {
    $body = $this->request->getRequestBody();

    if($body['password'] === $body['passwordChange'])
    {
      $employee = Application::$app->user;
      $password = password_hash($body['passwordChange'], PASSWORD_DEFAULT);
      
      $employee->updateAttribute( 'password', $password );
    }
    
    return $this->response->end( $this->render('profile', ['message' => "Password Changed Successfully"] ) );
  }

  //GET request
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
  
    return $this->response->end( $this->render('register', [
                                      'offices' => Application::$app->session->get('offices'),
                                      'managers' => Application::$app->session->get('managers')
                                     ]) );
                                
    //return $this->response->end( json_encode(Application::$app->session->get('offices') ) );
  }
  
  //POST request
  public function createUser()
  {
    $employee = Application::$app->user;
    
    //$employee->loadData( $this->request->getRequestBody() );
    
    return $this->response->end( $employee->insertRecord( $this->request->getRequestBody() ) );
    
    //return "Wow Grape!!";
    //header("Location:/");
    //return json_encode($_POST);
  }

}

?>
