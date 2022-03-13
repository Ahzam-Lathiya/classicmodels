<?php

namespace app\core;

class Container
{
  public array $entries = [];

  public function has(string $id): bool
  {
    return isset( $this->entries[$id] );
  }
  
  
  public function get(string $id)
  {
    //if there is explicit binding
    if($this->has($id) )
    {
      $entry = $this->entries[$id];
      
      //check if $id is callable bcoz in case of interfaces , interfaces are set against one of their concrete implementation
      if( is_callable($id) )
      {
        return $entry($this);
      }
      
      //if not, the $id simply becomes the concrete class name and is sent to the resolve method
      // OR the interface becomes the concrete class name and is sent to resolve()
      $id = $entry;
    }
    
    //if not then implement some sort of autowiring
    return $this->resolve($id);
  }
  
  
  //here $id means the name of the class and $concrete is initialized instance of the class
  public function set(string $id, string |callable | object $concrete): void
  {
    // if there is an initialized object set with the className as the $id
    if( is_object($concrete) )
    {
      // get all the properties and loop through them
      foreach(get_object_vars($concrete) as $property => $value)
      {
        //set the property along with the value in the "entries" array
        $this->set($property, $value);
      }
      
      return ;
    }
    
    //else if there is a callable or a string set
    $this->entries[$id] = $concrete;
  }

  
  public function setArray(string $id, array $arr)
  {
    /*
    foreach($arr as $key => $value)
    {
      if( is_array($value) )
      {
        $this->setArray($key, $value);
      }
      
      else
      {
        $this->set($key, $value);
      }
    }
    */
    $this->entries[$id] = $arr;
    
    return;
  }

  
  public function resolve(string $id)
  {
    //1: inspect the class that we are trying to get from the container
    $inspectClass = new \ReflectionClass($id);
    
    //what if the inspectClass is not a concrete class and can't be instantiated
    if( !$inspectClass->isInstantiable() )
    {
      throw new \Exception();
    }
    
    //2: inspect the constructor of the class
    $inspectConstruct = $inspectClass->getConstructor();
    
    //if there isn't any constructor for the class
    if( !$inspectConstruct )
    {
      return new $id;
    }
    
    //3: inspect the arguments for the constructor of the class i.e(dependencies)
    $inspectParams = $inspectConstruct->getParameters();
    
    //if there aren't any params
    if( !$inspectParams )
    {
      // simply return the fresh instance of the class
      return new $id;
    }

    //4: if the constructor param is a class then try to resolve that class using the container
    //basically that class will be recursively resolved by the resolve method
    
    $instantiableParams = array_map( function(\ReflectionParameter $param) use ($id, $inspectClass)
    {
      $name = $param->getName();
      
      $type = $param->getType();
      
      //if dependency is missing a type hint
      if (! $type)
      {
        throw new \Exception();
      }
      
      //if dependency is built-in data type
      if($type->isBuiltin() || $type instanceof \ReflectionUnionType )
      {
        //throw new \Exception("Failed to resolve class because of Union Type");
        
        //check if the built-in data type already has some value set to the property
        if( $this->has($name))
        {
          return $this->entries[$name];
        }
        
        $property = $inspectClass->getProperty($name);
        
        //if property has a default value
        if( $property->hasDefaultValue() )
        {
          return $property->getDefaultValue();
        }

        //else if parameter has default passed in constructor
        return $param->getDefaultValue();
      }
      
      
      //if dependency isn't a built-in class
      if($type instanceof \ReflectionNamedType && !$type->isBuiltin() )
      {
        //recursively call the get method with the class-Name of the dependency type
        return $this->get( $type->getName() );
      }
      
      throw new \Exception();
    
    }, $inspectParams);
    
    return $inspectClass->newInstanceArgs($instantiableParams );
  }
}

?>
