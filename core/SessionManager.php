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
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    return unserialize($this->redis->hGet($index, $key) );
  }


  public function set($key,$data)
  {    
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    $data = serialize($data);
    $this->redis->hSet($index, $key, $data);
  }


  public function remove($key)
  { 
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    $this->redis->hDel($index, $key);
  }
  
  
  public function destroy()
  {
    $index = "PHPREDIS_SESSION:" . $this->sessionID;
    $this->redis->del($index);
  }


  public function setSessionID($id)
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
    $index = "PHPREDIS_SESSION:" . $sessID;
    return $this->redis->hExists($index, "user");
  }
  
  //checks if user is already logged in from different system
  public function userExists($key)
  {
    $answer = [];
    
    //retrieve all sessionKeys and search all 'user' sub-keys
    $allSessions = $this->redis->keys("*");
    
    //loop through the keys searching for the userid
    foreach($allSessions as $session)
    {
      //if exists return the user ID along with the session in which it exists
      
      if( unserialize( $this->redis->hGet($session, "user") ) == $key)
      {
        $answer[] = [$session => $key];
        break;
      }
    }
    
    return $answer;
  }

}

?>
