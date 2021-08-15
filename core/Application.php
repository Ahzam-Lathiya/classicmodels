<?php

namespace app\core;

use app\core\db\Database;

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
  public Session $session;
  public ?UserModel $user;

  public function __construct($config)
  {
    $this->userClass = $config['userClass'];
    $this->db = new Database();
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
    $this->view = new View();
    $this->session = new Session();
    self::$app = $this;
    
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
    $this->session->destroy();
  }
  
  
  public function setController(Controller $controller)
  {
    $this->controller = $controller;
  }


  public function run()
  {
    try
    {
      echo $this->router->resolve();
    }

    catch(\Exception $e)
    {
      $this->response->setStatusCode($e->getCode());
      echo $this->view->renderView('errorView', ['exception' => $e] );
    }
  }


  public static function isGuest()
  {
    return !self::$app->user;
  }

}

?>
