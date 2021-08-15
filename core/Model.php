<?php

namespace app\core;

abstract class Model
{
  abstract public function primaryKey(): string;
  
  abstract public function tableName(): string;
  
  
  public function attributes(): array
  {
    $tableName = $this->tableName();
    $statement = Application::$app->db->prepare("SELECT COLUMN_NAME  FROM INFORMATION_SCHEMA.COLUMNS  WHERE TABLE_NAME = :table;");
    
    $statement->bindValue(':table', $tableName, \PDO::PARAM_STR);
    $statement->execute();
    
    $attributes = $statement->fetchAll(\PDO::FETCH_NUM );
    //[['abb'] ,['cd'], ['ef'], ['gh'], ['ij'], ['kl']]
    
    $selected = [];

    for($i=0; $i<count($attributes); $i++)
    {
      $selected[] = $attributes[$i][0];
    }
    
    return $selected;
  }


  public function fetchAllRecords()
  {
    $tableName = $this->tableName();
    
    $statement = Application::$app->db->prepare("SELECT * FROM $tableName;");
    
    $statement->execute();
    
    return $statement->fetchAll( \PDO::FETCH_ASSOC );
  }
  

  public function loadData($data)
  {
    foreach($data as $key => $value)
    {
      if( property_exists($this, $key) )
      {
        $this->{$key} = $value;
      }
    }
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
      
    }

    //join the INSERT INTO with tablename and VALUES part
    $sql = $sql . $fields . " " . $values . ";";
    
    $statement = Application::$app->db->prepare($sql);
  
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
    
    $ID = Application::$app->db->getLastID();
    
    return "Created new record with ID:$ID" . PHP_EOL;
  }


  public function __toString()
  {
    $attributes = $this->attributes();
    
    $json = [];
    
    foreach($attributes as $attribute)
    {
      $json[] = [$attribute => $this->{$attribute} ];
    }
    
    return json_encode($json);
  }


  public function updateRecord($data, $ID)
  {
    $primaryKey = $this->primaryKey();
    $tableName = $this->tableName();
  
    $query = "UPDATE $tableName ";
    $params = 'SET ';
    
    //remove the primary key from the data so that it isn't inserted in update query.
    unset($data[$primaryKey] );
  
    foreach($data as $key => $value)
    {
      if($key === array_key_last($data) )
      {
        $params = $params . $key . ' =' . " :$key" . ' ';
        break;
      }
      
      $params = $params . $key . ' =' . " :$key" . ', ';
    }
  
    //join the INSERT INTO with tablename and VALUES part
    $query = $query . $params . "WHERE $primaryKey = :productCode;";

    $statement = Application::$app->db->prepare($query);

    $params = [];
  
    foreach($data as $key => $value)
    {
      $bind = ":" . $key;
      $params[$bind] = $value;
    }
    
    $primaryKey = ':' . $primaryKey;
    
    $params[$primaryKey] = $ID;
    
    try
    {
      $statement->execute($params);
    }
    
    catch(\Exception $e)
    {
      return $e;
    }
    
    return "Record Updated Successfully";
    //return $statement->queryString;
  }

}

?>
