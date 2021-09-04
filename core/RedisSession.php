<?php

namespace app\core;


class RedisSession
{
  public $redisObj;

  public function __construct()
  {
    $this->redisObj = new \Redis();
    
    try
    {
      $this->redisObj->connect('127.0.0.1', '6379');
      //$this->redisObj->auth(['certainly']);
    }
    
    catch(\Exception $e)
    {
      throw new \RedisException();
    }
  }

  public function set($key, $value)
  {
    $this->redisObj->set($key, $value);
  }

  public function get($key)
  {
    return $this->redisObj->get($key) ?? false;
  }

  public function remove($key)
  {
    $this->redisObj->unlink($key);
  }

  public function destroy()
  {
    $this->redisObj->flushDb();
  }

}

?>
