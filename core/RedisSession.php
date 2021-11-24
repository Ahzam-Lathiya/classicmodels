<?php

namespace app\core;


class RedisSession
{
  public $redisObj;

  public function __construct()
  {
    $this->redisObj = new \Redis();
    
    $this->redisObj->connect('127.0.0.1', '6379');
    //$this->redisObj->auth(['certainly']);
    session_set_save_handler(
        array($this, 'open'),
        array($this, 'close'),
        array($this, 'read'),
        array($this, 'write'),
        array($this, 'destroy'),
        array($this, 'gc')
    );
    
    if( !isset($_SESSION) )
    {
      session_start();
    }
  }

  public function set($key, $value)
  {
    //$this->redisObj->set($key, $value);
    $_SESSION[$key] = $value;
  }

  public function get($key)
  {
    //return $this->redisObj->get($key) ?? false;
    return $_SESSION[$key] ?? false;
  }

  public function remove($key)
  {
    //$this->redisObj->unlink($key);
    unset( $_SESSION[$key] );
  }

  public function destroy()
  {
    //$this->redisObj->flushDb();
    unset( $_SESSION );
  }

}

?>
