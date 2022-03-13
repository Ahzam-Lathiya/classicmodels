<?php

namespace app\core;

use Swoole\Http\Request as SwooleRequest;

class Request
{
  public ?SwooleRequest $swooleRequest = null;

  public function __construct(SwooleRequest $request)
  {
    $this->swooleRequest = $request;
  }

  public function getPath()
  {
    //get the requested URI as string or just '/' if $_SERVER returns null
    //$path = $_SERVER['REQUEST_URI'] ?? '/';
    $path = $this->swooleRequest->server['request_uri'] ?? '/';
    
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
    //$path = $_SERVER['REQUEST_URI'] ?? '/';
    $path = $this->swooleRequest->server['request_uri'] ?? '/';
    
    $pathArr = explode('/', $path);
    
    if( count($pathArr) > 3 )
    {
      $exp = preg_match_all('/[S][0-9]+\_[0-9]+/m', $path, $array);
      return $array[0][0];
    }
    
  }
  
  
  public function getOrderPath()
  {
    //$path = $_SERVER['REQUEST_URI'] ?? '/';
    $path = $this->swooleRequest->server['request_uri'] ?? '/';
    
    $pathArr = explode('/', $path);
    
    if( count($pathArr) > 3 )
    {
      $exp = preg_match_all('/\d{3,5}/m', $path, $array);
      return $array[0][0];
    }
  }


  public function getEndofPath()
  {
    //$path = $_SERVER['REQUEST_URI'] ?? '/';
    $path = $this->swooleRequest->server['request_uri'] ?? '/';
    
    $pathArr = explode('/', $path);
    
    return ;
  }
  
  
  public function getURLParams()
  {
    $queryString = $this->swooleRequest->server['query_string'] ?? '';
    
    $allParams = [];
    
    if($queryString != '')
    {
      $params = explode("&", $queryString);
      
      foreach($params as $param)
      {
        $key = explode("=", $param)[0];
        $value = explode("=", $param)[1];

        $allParams[$key] = $value;
      }

    }
    
    return $allParams;
  }
  
  
  public function getMethod()
  {
    //return $_SERVER['REQUEST_METHOD'];
    return $this->swooleRequest->server['request_method'] ?? '/';
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
    return $this->swooleRequest->post;
  }

  public function getSiteNameFromPath()
  {
    $url = $this->swooleRequest->server['request_uri'] ?? '/';
    
    return explode('/', $url)[1];
  }

}

?>
