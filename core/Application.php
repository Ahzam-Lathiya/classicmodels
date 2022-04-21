<?php

namespace app\core;

use app\core\db\Database;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as Response;
use app\core\exceptions\ForbiddenException;
use app\core\exceptions\JsonException;

class Application
{
  public Router $router;
  public Request $request;
  public Response $response;
  public Database $db;
  public string $userClass = '';
  public ?Controller $controller = null;
  public static Application $app;
  public string $layout = 'main';
  public View $view;
  public SessionManager $session;
  public ?UserModel $user;
  public array $globalConfig = [];
  public Container $container;

  public function __construct(array $globalConfig)
  {
    $this->container = new Container();
    
    $this->userClass = $globalConfig['user_class'];
    $this->db = new Database($globalConfig['DB_CONFIG']);

    //router is initialized here because all the routes have to be registered against the router in the bootstrapping phase
    $this->router = new Router();
    $this->view = new View();
    
    $this->globalConfig = $globalConfig;
    self::$app = $this;
    
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
    
    $permitted_chars = $this->globalConfig['permit_chars'];
    
    //generate a rand
    $rand = substr(str_shuffle($permitted_chars), 0, 20);
    
    $this->session->setSessionID($rand);
    
    // $_SESSION['user'] = 1088;
    $this->session->set('user', $primaryValue);
    
    $this->response->cookie($key = 'userSess', $value = $rand);
    
    return true;
  }

  public function logout()
  {
    //remove the entire hash of current session ID
    $this->session->destroy();
    $this->user = null;
  }
  
  
  public function setController(Controller $controller)
  {
    $this->controller = $controller;
  }


  public function run($swooleRequest, Response $swooleResponse)
  {
    try
    {
      $sessID = '';
      
      $this->session = new SessionManager();

      $this->request = new Request($swooleRequest);
      $this->response = $swooleResponse;
      
      $this->router->setReqResp($this->request, $this->response);
      

      if( $this->request->swooleRequest->cookie )
      {
        $sessID = $this->request->swooleRequest->cookie['userSess'];
      }
      
      
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
    
      return $this->router->resolve();
    }
    
    
    catch(JsonException $e)
    {
      $this->response->setStatusCode($e->getCode() );
      
      $message = $e->getMessage();
      return $this->response->end( json_encode([
      'code'  => $e->getCode(),
      'message' => $message
      ]) );
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
