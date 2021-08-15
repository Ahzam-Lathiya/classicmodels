<?php

namespace app\core;

use app\core\Application;

abstract class UserModel extends Model
{    
  abstract public function tableName(): string;
  
  abstract public function primaryKey(): string;
  
  public function findOne($where) // [email => abc@xyz.com]
  {
    //$tableName = static::tableName();
    $tableName = $this->tableName();
    $attributes = array_keys($where);
    
    //transform "name" into "name = :name"
    $transformed_string = array_map( fn($attr) => "$attr = :$attr", $attributes);
    
    //join the strings with 'AND'
    $sql = implode('AND', $transformed_string);
    
    //SELECT * FROM $tableName WHERE email = :email AND firstname = :firstname
    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $sql");
    
    foreach($where as $key => $value)
    {
      $statement->bindValue(":$key", $value);
    }
    
    $statement->execute();
    
    //LATE STATIC BINDING. static refers to the class that will be called at runtime
    return $statement->fetchObject(static::class);
  }
  
  public function updateAttribute($attr, $value)
  {
    $empID = (string) Application::$app->session->get('user');
    
    $tableName = $this->tableName();
    
    $statement = Application::$app->db->prepare("UPDATE $tableName SET $attr = :pass WHERE employeeNumber = :id;");
    
    $statement->bindValue(":pass", $value);
    $statement->bindValue(":id", $empID);
    
    $statement->execute();
    
    return true;
  }
  
}

?>
