<?php

namespace app\core;

use app\core\db\Database;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as Response;

class Application
{
  public Router $router;
  public Request $request;
  public Response $response;
  public Database $db;
  public string $userClass;
  public ?Controller $controller = null;
  public static Application $app;
  public string $layout = 'main';
  public View $view;
  public SessionManager $session;
  public ?UserModel $user;

  public function __construct($config, $swooleRequest, Response $swooleResponse)
  {
    $this->userClass = $config['userClass'];
    $this->db = new Database($config['DB_CONFIG']);
    $this->request = new Request( $swooleRequest);
    $this->response = $swooleResponse;
    $this->router = new Router($this->request, $this->response);
    $this->view = new View();
    $this->session = new SessionManager();
    self::$app = $this;
    
    
    //$primaryValue = $this->session->get('user');
    //use session ID instead of 'user'
    
    /*
    $primaryValue = $this->session->get('user');
    
    //if primary value exists then it means the visitor is logged in
    if($primaryValue)*/
    
    $sessID = $this->request->swooleRequest->cookie['userSess'];
    
    if($this->session->sessionExists($sessID) >= 1)
    {
      $this->session->sessionID = $sessID;
      $primaryValue = $this->session->get('user');
      $primaryKey = (new $this->userClass() )->primaryKey();
      $this->user = (new $this->userClass() )->findOne( [$primaryKey => $primaryValue] );
    }
    
    
    else
    {
      $this->user = null;
    }
    
    /*
    $this->user = (new $this->userClass() );
    
    //if user isn't logged in
    if( !$this->user->employeeNumber )
    {
      $this->user = null;
    }
    
    catch(\Exception $e)
    {
      $this->response->setStatusCode($e->getCode());
      return $this->response->end( $this->view->renderView('errorView', ['exception' => $e] ) );
    }
    */
    
  }

  public function login(UserModel $user)
  {
    $this->user = $user;
    $primaryKey = $user->primaryKey();
    
    $primaryValue = $user->{$primaryKey};
    
    //generate random alphanum to store it as session id.
    //init a redis session and dump the user id or object with the session id.
    //set a cookie with the same sess ID with user key.
    
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    //generate a rand
    $rand = substr(str_shuffle($permitted_chars), 0, 20);
    
    $this->session->setSession($rand);
    
    // $_SESSION['user'] = 1088;
    $this->session->set('user', $primaryValue);
    
    $this->response->cookie($key = 'userSess', $value = $rand);
    
    return true;
  }

  public function logout()
  {
    //$this->session->remove( $this->user->employeeNumber );
    //$this->session->remove( 'user' );
    $this->session->destroy();
    $this->user = null;
  }
  
  
  public function setController(Controller $controller)
  {
    $this->controller = $controller;
  }


  public function run()
  {
    
    try
    {
      return $this->router->resolve();
    }

    catch(\Exception $e)
    {
      $this->response->setStatusCode($e->getCode());
      return $this->response->end( $this->view->renderView('errorView', ['exception' => $e] ) );
    }
    
  }


  public static function isGuest()
  {
    return !self::$app->user;
  }

}

?>
