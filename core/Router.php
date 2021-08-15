<?php

namespace app\core;

use app\core\exceptions\NotFoundException;

class Router
{
  public array $routes = [];
  
  public Request $request;
  public Response $response;
  
  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
    
    $callback = $this->routes[$method][$path] ?? false;
    
    if($callback === false)
    {
      throw new NotFoundException();
    }

    else
    {
    
      if(is_array($callback) )
      {
        $controller = new $callback[0]();
        
        //set the controller according to the request through the app
        Application::$app->setController($controller);
        
        return call_user_func( array($controller, $callback[1]), $this->request, $this->response );
      }

    }
    
  }

}

?>
