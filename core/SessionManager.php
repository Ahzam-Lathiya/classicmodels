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
    /*
    $key = "PHPREDIS_SESSION:" . $this->sessionID . ":" . $key;
    return unserialize($this->redis->get($key) );
    */
    
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    return unserialize($this->redis->hGet($index, $key) );
  }


  public function set($key,$data)
  {
    /*
    $key = "PHPREDIS_SESSION:" . $this->sessionID . ":" . $key;
    $data = serialize($data);
    $this->redis->set($key, $data);
    */
    
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    $data = serialize($data);
    $this->redis->hSet($index, $key, $data);
  }


  public function remove($key)
  {
    /*
    $key = "PHPREDIS_SESSION:" . $this->sessionID . ":" . $key;
    $this->redis->delete($key);
    */
    
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    $this->redis->hDel($index, $key);
  }
  
  
  public function destroy()
  {
    //$this->redis->flushDb();
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    $this->redis->del($index);
  }


  public function setSession($id)
  {
    $this->sessionID = $id;
  }

  public function update($key, $value)
  {
    $this->remove($key);
    $this->set($key, $value);
  }
  
  
  public function sessionExists($sessID)
  {
    /*
    $key = "PHPREDIS_SESSION:" . $sessID . ":" . "user";
    return $this->redis->exists($key);
    */
    
    $index = "PHPREDIS_SESSION:" . $sessID;
    return $this->redis->hExists($index, "user");
    
  }
  
  public function keyExists($key)
  {
    //retrieve all sessionKeys and search all 'user' sub-keys 
  }

}

?>
