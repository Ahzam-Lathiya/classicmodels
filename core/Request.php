<?php

namespace app\core;

class Request
{

  public function getPath()
  {
    //get the requested URI as string or just '/' if $_SERVER returns null
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    
    //find position of the '?' in request url if it exists
    $quesPos = strpos($path, '?');
    
    $pathArr = explode("/", $path);
    
    if(!$quesPos)
    {
      if( count($pathArr) > 3)
      {
        $path = implode("/", Array_slice($pathArr, 0, 3) );
      }
    
      return $path;
    }
    
    //substring: stripped from zero-th index to the index of '?'
    return substr($path, 0, $quesPos);
  }
  
  
  public function getProductPath()
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    
    $pathArr = explode('/', $path);
    
    if( count($pathArr) > 3 )
    {
      $exp = preg_match_all('/[S][0-9]+\_[0-9]+/m', $path, $array);
      return $array[0][0];
    }
    
  }
  
  
  public function getOrderPath()
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    
    $pathArr = explode('/', $path);
    
    if( count($pathArr) > 3 )
    {
      $exp = preg_match_all('/\d{3,5}/m', $path, $array);
      return $array[0][0];
    }
  }


  public function getEndofPath()
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    
    $pathArr = explode('/', $path);
    
    return ;
  }
  
  
  public function getMethod()
  {
    return $_SERVER['REQUEST_METHOD'];
  }
  
  
  public function isGet()
  {
    return $this->getMethod() === 'GET';
  }
  
  
  public function isPost()
  {
    return $this->getMethod() === 'POST';
  }
  
  
  public function getRequestBody()
  {
    return $_POST;
  }

}

?>
