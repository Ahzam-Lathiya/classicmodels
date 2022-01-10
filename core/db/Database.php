<?php

namespace app\core\db;

class Database
{
  public \PDO $pdo;
  public string $dsn = '';
  public string $user = '';
  public string $pass = '';

  public function __construct($dbConfig)
  {
    //dsn: Domain Service Name
    $this->dsn = $dbConfig['dsn'];
    $this->user = $dbConfig['user'];
    $this->pass = $dbConfig['pass'];
  }
  
  

  public function prepare($sql)
  {
    //connecting with the \PDO() constructor
    $this->pdo = new \PDO($this->dsn, $this->user, $this->pass);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
    return $this->pdo->prepare($sql);
  }


  public function getLastID()
  {
    return $this->pdo->lastInsertId();
  }


}

?>
