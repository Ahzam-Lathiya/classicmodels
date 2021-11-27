<?php

namespace app\core\db;

class Database
{
  public \PDO $pdo;

  public function __construct($dbConfig)
  {
    //dsn: Domain Service Name
    $dsn = $dbConfig['dsn'];
    $user = $dbConfig['user'];
    $pass = $dbConfig['pass'];
    
    $this->pdo = new \PDO($dsn, $user, $pass);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }
  
  public function prepare($sql)
  {
    return $this->pdo->prepare($sql);
  }

  public function getLastID()
  {
    return $this->pdo->lastInsertId();
  }

}

?>
