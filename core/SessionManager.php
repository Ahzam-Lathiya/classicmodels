<?php 

namespace app\core;

class SessionManager
{
  public $redis;
  public $sessionID;

  public function __construct()
  {
    $this->redis = new \Redis();// Create phpredis instance
    $this->redis->connect('127.0.0.1', 6379); // connect redis
  }


  public function get($key)
  {
    $key = "PHPREDIS_SESSION:" . $this->sessionID . ":" . $key;
    return unserialize($this->redis->get($key) );
  }


  public function set($key,$data)
  {
    $key = "PHPREDIS_SESSION:" . $this->sessionID . ":" . $key;
    $data = serialize($data);
    $this->redis->set($key, $data);
    
  }


  public function remove($key)
  {
    $key = "PHPREDIS_SESSION:" . $this->sessionID . ":" . $key;
    $this->redis->delete($key);
  }
  
  
  public function destroy()
  {
    $this->redis->flushDb();
  }


  public function setSession($id)
  {
    $this->sessionID = $id;
  }
  
  
  public function sessionExists($sessID)
  {
    $key = "PHPREDIS_SESSION:" . $sessID . ":" . "user";
    return $this->redis->exists($key);
  }

}

?>
