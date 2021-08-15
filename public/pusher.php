<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/event-stream');
header("Cache-Control: no-cache");

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

$db = new Database();

$statement = $db->prepare("SELECT productCode, productName, productLine, productScale, productVendor, productDescription, quantityInStock, buyPrice, MSRP, action FROM products_audit WHERE auditTime + interval 20 second > CURRENT_TIMESTAMP();");


while(1)
{
  $statement->execute();
    
  $data = json_encode( $statement->fetchAll(\PDO::FETCH_ASSOC) );

  if($data !== '')
  {
    echo "event: message\n",
         "data: $data", "\n\n";
        
    while (ob_get_level() > 0)
    {
      ob_end_flush();
    }

    flush();
  }
        
  if( connection_aborted() )
  {
    exit;
  }
  
  sleep(20);
}

?>
