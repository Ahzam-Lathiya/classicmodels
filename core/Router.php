<?php

namespace app\core;

use app\core\exceptions\NotFoundException;
use Swoole\Http\Response as Response;

class Router
{
  public array $routes = [];
  
  public Request $request;
  public Response $response;
  
  public function __construct()
  {
    //$this->request = $request;
    //$this->response = $response;
  }
  
  
  public function setReqResp($swooleRequest, Response $swooleResponse)
  {
    $this->request = $swooleRequest;
    $this->response = $swooleResponse;
  }


  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();

    /*
    if($path === '/favicon.ico')
    {
      return $this->response->end();
    }
    */

    $callback = $this->routes[$method][$path] ?? false;

    $controller = new $callback[0]();
    
    //set the controller according to the request through the app
    Application::$app->setController($controller);
    
    return call_user_func( array($controller, $callback[1]), $this->request, $this->response );

    /*
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
    */
    
  }

}

?>
