<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

class Offices extends Model
{
  public string $officeCode = '';
  public string $city = '';
  public string $phone = '';
  public string $addressLine1 = '';
  public string $addressLine2 = '';
  public string $state = '';
  public string $country = '';
  public string $postalCode = '';
  public string $territory = '';
  
  public function tableName(): string
  {
    return 'offices';
  }

  
  public function primaryKey(): string
  {
    return "officeCode";
  }
  
  
  public function getCities()
  {
    $statement = Application::$app->db->prepare("SELECT officeCode, city FROM offices;");
    
    $statement->execute();
    
    return $statement->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_COLUMN );
  }
  
}

?>
