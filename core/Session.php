<?php

namespace app\core;


class Session
{
  public function __construct()
  {
    $redisObj = new \Redis();
    
    $redisObj->connect('127.0.0.1', 6379);
    
    $sessHandler = new RedisSessionHandler($redisObj);
    
    
    session_set_save_handler(
        array($sessHandler, 'open'),
        array($sessHandler, 'close'),
        array($sessHandler, 'read'),
        array($sessHandler, 'write'),
        array($sessHandler, 'destroy'),
        array($sessHandler, 'gc')
    );
    
    //echo session_status() . PHP_EOL;
    if(session_status() != PHP_SESSION_ACTIVE)
    {
      session_start();
    }
    //session_start();
    //new SessionManager();
  }

  public function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public function get($key)
  {
    return $_SESSION[$key] ?? false;
  }

  public function remove($key)
  {
    unset( $_SESSION[$key] );
  }

  public function destroy()
  {
    unset( $_SESSION );

    session_destroy();
  }

}

?>
