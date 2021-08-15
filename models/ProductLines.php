<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

class ProductLines extends Model
{
  public string $productLine = '';
  public string $textDescription = '';
  public string $htmlDescription = '';
  public string $image = '';

  public function primaryKey(): string
  {
    return 'productLine';
  }
  
  public function tableName(): string
  {
    return 'productlines';
  }
  
  public function fetchProductLines()
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT * FROM $tableName;");
    
    $statement->execute();
    
    return $statement->fetchAll( \PDO::FETCH_ASSOC );
  }
  
}

?>
