<?php

namespace app\core;

class View
{
  public string $title = '';

  protected function fetchLayout()
  {
    $layout = Application::$app->layout;
  
    ob_start();
    
    require_once "../views/layouts/" . $layout . ".php";
  
    return ob_get_clean();
  }
  
  
  protected function fetchView($view, $params=[])
  {
    foreach($params as $key => $value)
    {
      $$key = $value;
    }
  
    ob_start();
    
    require_once "../views/$view.php";
    
    return ob_get_clean();
  }
  
  
  public function renderView($view, $params=[])
  {
    $viewContent = $this->fetchView($view, $params);
    
    $layoutContent = $this->fetchLayout();
    
    return str_replace('{{ content }}', $viewContent, $layoutContent);
  }


}

?>
