<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

class Customers extends Model
{

  public int $customerNumber = 0;
  public string $customerName = '';
  public string $contactLastName = '';
  public string $contactFirstName = '';
  public string $phone = '';
  public string $addressLine1 = '';
  public string $addressLine2 = '';
  public string $city = '';
  public string $state = '';
  public string $postalCode = '';
  public string $country = '';
  public int $salesRepEmployeeNumber = 0;
  public float $creditLimit = 0.0;

  public function tableName(): string
  {
    return 'customers';
  }
  
  
  public function primaryKey(): string
  {
    return 'customerNumber';
  }

  public function fetchCustomer($id)
  {
    $tableName = $this->tableName();
    
    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE customerNumber = :id;");
    
    $statement->bindValue(':id', $id);
    $statement->execute();
    
    return $statement->fetchObject();
  }
  
}

?>
