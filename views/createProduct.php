<?php

$this->title = 'Create Product';
?>

<h1>Create new product</h1>

<form action="/products/addProduct" method="POST">

  <div class="form-row">
  
  <div class="form-group col-md-5">
    <label>Product Code</label>
    <input name="productCode" type="text" class="form-control" id="inputAddress" placeholder="productCode">
  </div>
  
  <div class="form-group col-md-5">
    <label>Product Name</label>
    <input name="productName" type="text" class="form-control" id="inputAddress" placeholder="productName">
  </div>
    
  <div class="form-group col-md-4">
    <label>Product Line</label>
    <select name="productLine" id="inputState" class="form-control">
      <?php 
      foreach($productLines as $key => $value)
      {
        $value = $value['productLine'];
        echo "<option value='$value'>" . $value . "</option>";
      }
      ?>
    </select>
  </div>

  
  <div class="form-group col-md-4">
    <label>Product Scale</label>
    <select name="productScale" id="inputState" class="form-control">
      <?php 
      foreach($productScales as $key => $value)
      {
        $value = $value['productScale'];
        echo "<option value=$value>" . $value . "</option>";
      }
      ?>
    </select>
  </div>
  
  <div class="form-group col-md-5">
    <label>Vendor</label>
    <input name="productVendor" type="text" class="form-control" id="inputAddress" placeholder="productVendor">
  </div>

  <div class="form-group col-md-5">
    <label>Description</label>
    <input name="productDescription" type="text" class="form-control" id="inputAddress" placeholder="productDescription">
  </div>
  
  <div class="form-group col-md-5">
    <label>Stock Quantity</label>
    <input name="quantityInStock" type="text" class="form-control" id="inputAddress" placeholder="quantity">
  </div>
  
  <div class="form-group col-md-5">
    <label>Buy Price</label>
    <input name="buyPrice" type="text" class="form-control" id="inputAddress" placeholder="buyPrice">
  </div>
  
  <div class="form-group col-md-5">
    <label>MSRP</label>
    <input name="MSRP" type="text" class="form-control" id="inputAddress" placeholder="MSRP">
  </div>
  
  <input type="submit">
  
</form>
