<?php

namespace app\core;

class Controller
{
  public string $layout = 'main';
  
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
