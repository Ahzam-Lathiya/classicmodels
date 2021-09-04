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
  public RedisSession $session;
  public ?UserModel $user;

  public function __construct($config, $swooleRequest, Response $swooleResponse)
  {
    $this->userClass = $config['userClass'];
    $this->db = new Database();
    $this->request = new Request( $swooleRequest);
    $this->response = $swooleResponse;
    $this->router = new Router($this->request, $this->response);
    $this->view = new View();
    $this->session = new RedisSession();
    self::$app = $this;
    
    
    //$primaryValue = $this->session->get('user');
    //use session ID instead of 'user'
    
    $primaryValue = $this->session->get('user');
    
    //if primary value exists then it means the visitor is logged in
    if($primaryValue)
    {
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
    */
  }

  public function login(UserModel $user)
  {
    $this->user = $user;
    $primaryKey = $user->primaryKey();
    
    $primaryValue = $user->{$primaryKey};
    
    // $_SESSION['user'] = 1088;
    $this->session->set('user', $primaryValue);
    return true;
  }

  public function logout()
  {
    //$this->session->remove( $this->user->employeeNumber );
    $this->session->remove( 'user' );
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
