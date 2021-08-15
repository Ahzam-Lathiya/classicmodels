<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

class Orders extends Model
{

  public int $orderNumber = 0;
  public string $orderDate = '';
  public string $requiredDate = '';
  public string $shippedDate = '';
  public string $status = '';
  public string $comments = '';
  public int $customerNumber = 0;

  public function tableName(): string
  {
    return 'orders';
  }
  

  public function primaryKey(): string
  {
    return 'orderNumber';
  }

  
  public function getAllOrders()
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT * FROM $tableName;");
    
    $statement->execute();
    return $statement->fetchAll();
  }


  public function getStatusTypes()
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT DISTINCT status FROM $tableName;");
    
    $statement->execute();
    
    return $statement->fetchAll();
  }


  public function fetchSingleOrder($id)
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT orderNumber, orderDate, requiredDate, shippedDate, status, comments, customerNumber, customerName FROM orders INNER JOIN customers USING (customerNumber) WHERE orderNumber = :ordID;");
    
    $statement->bindValue(':ordID', $id);
    $statement->execute();
    
    return $statement->fetchObject();
  }


  public function fetchOrderView($id)
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("CALL sp_completeOrderdetail(:id);");
    
    $statement->bindValue(":id", $id);
    $statement->execute();
    
    return $statement->fetchAll();
  }

}

?>
