<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

class Products extends Model
{
  public string $productCode = '';
  public string $productName = '';
  public string $productLine = '';
  public string $productScale = '';
  public string $productVendor = '';
  public string $productDescription = '';
  public int $quantityInStock = 0;
  public float $buyPrice = 0.0;
  public float $MSRP = 0.0;
  
  public int $pagRecords = 50;

  public function tableName(): string
  {
    return 'products';
  }
  
  
  public function primaryKey(): string
  {
    return 'productCode';
  }
  
  public function fetchAllRecords()
  {
    $tableName = $this->tableName();
    $primaryKey = $this->primaryKey();

    $statement = Application::$app->db->prepare("SELECT productCode, productName, productLine, productScale, productVendor, quantityInStock, buyPrice, MSRP FROM $tableName ORDER BY LENGTH($primaryKey), $primaryKey ASC;");
    
    $statement->execute();
    return $statement->fetchAll( \PDO::FETCH_ASSOC );
  }
  
  
  public function fetchPaginatedRecords($offset)
  {
    $tableName = $this->tableName();
    $primaryKey = $this->primaryKey();
    
    $numRecords = $this->pagRecords;
    
    //calculate offset on basis of page number and number of records(50 in this case) to be returned
    $offset = ($offset - 1) * $numRecords;
    
    $statement = Application::$app->db->prepare("SELECT productCode, productName, productLine, productScale, productVendor, quantityInStock, buyPrice, MSRP FROM $tableName ORDER BY LENGTH($primaryKey), $primaryKey ASC LIMIT $offset, $numRecords");
    
    $statement->execute();
    
    return $statement->fetchAll( \PDO::FETCH_ASSOC );
  }
  

  public function getProduct($id)
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE productCode = :prod;");
    
    $statement->bindValue(":prod", $id);
    $statement->execute();
    
    return $statement->fetchObject();
  }
  
  
  public function getProductNames()
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT productName FROM $tableName;");
    
    $statement->execute();
    
    return $statement->fetchAll();
  }
  
  
  public function fetchProductScales()
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT DISTINCT productScale FROM $tableName;");
    
    $statement->execute();
    
    return $statement->fetchAll( \PDO::FETCH_ASSOC );
  }
  
  public function fetchProductAudits()
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT productCode, productName, productLine, productScale, productVendor, productDescription, quantityInStock, buyPrice, MSRP, action FROM products_audit WHERE auditTime + interval 50 second > CURRENT_TIMESTAMP();");
    
    $statement->execute();
    
    return $statement->fetchAll(\PDO::FETCH_ASSOC);
  }
  
}

?>
