<?php

namespace app\models;

use app\core\UserModel;
use app\core\Application;

class Employees extends UserModel
{
  public int $employeeNumber = 0;
  public string $lastName = '';
  public string $firstName = '';
  public string $extension = '';
  public string $email = '';
  public string $password = '';
  public string $officeCode = '';
  public ?int $reportsTo = 0;
  public string $jobTitle = '';
  
  
  public function tableName(): string
  {
    return "employees";
  }
  
  public function primaryKey(): string
  {
    return "employeeNumber";
  }
  

  public function insertRecord($data)
  {
    $sql = "INSERT INTO ";
    $fields = $this->tableName() . "(";
    $values = "VALUES(";
    
    foreach($data as $key => $value)
    {
      if( $key === array_key_last($data) )
      {
        $values = $values . " :" . $key . ")";
        
        $fields = $fields . $key . ")";
        break;
      }
      
      $values = $values . " :" . $key . ",";
      $fields = $fields . $key . ",";
      
      if( $key === 'email')
      {
        $values = $values . " :" . "password" . ",";
        
        $fields = $fields . "password" . ",";
        continue;
      }
    }

    //join the INSERT INTO with tablename and VALUES part  
    $sql = $sql . $fields . " " . $values . ";";
    
    $statement = Application::$app->db->prepare($sql);
  
    $data['password'] = password_hash('1234', PASSWORD_DEFAULT);
    
    foreach($data as $key => $value)
    {
      $bind = ":" . $key;
      $statement->bindValue($bind, $value);
    }
    
    try
    {
      $statement->execute();
    }
    
    catch(\Exception $e)
    {
      return $e;
    }
    
    $empID = Application::$app->db->getLastID();
    
    return "Created new record with ID:$empID" . PHP_EOL;
  }
  
  
  public function verifyPass($loginID, $loginPass)
  {
    $statement = Application::$app->db->prepare("SELECT password FROM employees WHERE employeeNumber = :empNum;");
    
    $statement->bindValue(":empNum", $loginID);
    
    $statement->execute();
    $pass = $statement->fetchObject();
    
    return password_verify($loginPass, $pass->password);
  }

  
  public function getFullName()
  {
    $empID = (string) Application::$app->session->get('user');
      
    $statement = Application::$app->db->prepare("SELECT firstName, lastName FROM employees WHERE employeeNumber = :empNum;");
    
    $statement->bindValue(":empNum", $empID);
    
    $statement->execute();
    $fullname = $statement->fetchObject();
    return "(" . $fullname->firstName . "_" . $fullname->lastName . ")";
  }

  
  public function verifyID($id)
  {
    $statement = Application::$app->db->prepare("SELECT 1 WHERE EXISTS( SELECT 1 FROM employees WHERE employeeNumber = :empNum);");
    
    $statement->bindValue(":empNum", $id);
    
    $statement->execute();
    
    return $statement->fetchObject();
  }


  public function getManagers()
  {
    $statement = Application::$app->db->prepare("CALL sp_getManagers;");
    
    $statement->execute();
    return $statement->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_COLUMN );
  }
  
  
  public function getRow($id)
  {
    $statement = Application::$app->db->prepare("SELECT * FROM employees WHERE employeeNumber = :empNum;");
    
    $statement->bindValue(":empNum", $id);
    
    $statement->execute();
    return $statement->fetchObject();
  }
  
}

?>
