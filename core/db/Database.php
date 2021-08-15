<?php

namespace app\core\db;

class Database
{
  public \PDO $pdo;

  public function __construct()
  {
    //dsn: Domain Service Name
    $dsn = "mysql:host=localhost;port=3306;dbname=classicmodels";
    $user = "root";
    $pass = "certainly";
    
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
