<?php

namespace app\core;
use Swoole\Http\Response as Response;

class Controller
{
  public string $layout = 'main';
  protected Request $request;
  protected Response $response;
  
  public function setRequestObject($request)
  {
    $this->request = $request;
  }
  
  public function setResponseObject($response)
  {
    $this->response = $response;
  }
  
  public function render($view, $params = [])
  {
    return Application::$app->view->renderView($view, $params);
  }
  
  public function setLayout($layout)
  {
    Application::$app->layout = $layout;
    $this->layout = Application::$app->layout;
  }

}

?>
