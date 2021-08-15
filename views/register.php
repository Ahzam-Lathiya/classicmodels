<?php

$this->title = 'Register';
?>

<h1>Register a user</h1>

<form action="/addUser" method="POST">

  <div class="form-row">
  
  <div class="form-group col-md-5">
    <label>FirstName</label>
    <input name="firstName" type="text" class="form-control" id="inputAddress" placeholder="FirstName">
  </div>
  
  <div class="form-group col-md-5">
    <label>LastName</label>
    <input name="lastName" type="text" class="form-control" id="inputAddress" placeholder="LastName">
  </div>
    
  <div class="form-group col-md-5">
    <label>Email</label>
    <input name="email" type="email" class="form-control" id="inputEmail4" placeholder="Email">
  </div>
  
  <div class="form-group col-md-5">
    <label>JobTitle</label>
    <input name="jobTitle" type="text" class="form-control" id="inputEmail4" placeholder="jobtitle">
  </div>
  
  <div class="form-group col-md-5">
    <label>Extension</label>
    <input name="extension" type="text" class="form-control" id="inputEmail4" placeholder="extension">
  </div>

    <div class="form-group col-md-4">
      <label>Office/Branch</label>
      <select name="officeCode" id="inputState" class="form-control">
        <?php 
        foreach($offices as $id => $city)
        {
          echo "<option value=$id>" .$city[0] . "</option>";
        }
        ?>
      </select>
    </div>

    
    <div class="form-group col-md-4">
      <label>ReportsTo</label>
      <select name="reportsTo" id="inputState" class="form-control">
        <option>None</option>
        <?php 
        foreach($managers as $id => $fullName)
        {
          echo "<option value=$id>" . $fullName[0] . "</option>";
        }
        ?>
      </select>
    </div>
    
  </div>
    
  <button type="submit" class="btn btn-primary">Create User Confirm</button>
  
</form>
