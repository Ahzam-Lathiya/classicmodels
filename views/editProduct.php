<?php

$this->title = 'Edit Product';
?>

<h1>Edit product</h1>

<form id="editForm" action="/products/editProduct" method="POST">

  <div class="form-row">
  
  <?php
  
  echo "<div class=\"form-group col-md-5\">" . "<label>Product Code</label>" . "<input name=\"productCode\" type=\"text\" class=\"form-control\" id=\"inputAddress1\" value=\"$product->productCode\" placeholder=\"productCode\" readonly>
  </div>";
  
  echo "<div class=\"form-group col-md-5\">" . "<label>Product Name</label>" . 
  "<input name=\"productName\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"$product->productName\" placeholder=\"productName\"></div>";
  
  echo "<div class=\"form-group col-md-4\">" . "<label>Product Line</label>" . "<select name=\"productLine\" id=\"inputState\" class=\"form-control\">";
  
  foreach($productLines as $key => $value)
  {
    $value = $value['productLine'];
    echo "<option value='$value'>" . $value . "</option>";
  }
  echo "</select></div>";

  
  echo "<div class=\"form-group col-md-4\">" . "<label>Product Scale</label>" . "<select name=\"productScale\" id=\"inputState\" class=\"form-control\">";
  
  foreach($productScales as $key => $value)
  {
    $value = $value['productScale'];
    echo "<option value=$value>" . $value . "</option>";
  }
  echo "</select></div>";
  
  echo "<div class=\"form-group col-md-5\">" . "<label>Vendor</label>" . 
  "<input name=\"productVendor\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"$product->productVendor\" placeholder=\"productVendor\"></div>";

  echo "<div class=\"form-group col-md-5\">" . "<label>Description</label>" . 
  "<input name=\"productDescription\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"$product->productDescription\" placeholder=\"productDescription\"></div>";
  
  echo "<div class=\"form-group col-md-5\">" . "<label>Stock quantity</label>" . 
  "<input name=\"quantityInStock\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"$product->quantityInStock\" placeholder=\"quantityInStock\"></div>";
  
  echo "<div class=\"form-group col-md-5\">" . "<label>Buy Price</label>" . 
  "<input name=\"buyPrice\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"$product->buyPrice\" placeholder=\"buyPrice\"></div>";
  
  echo "<div class=\"form-group col-md-5\">" . "<label>MSRP</label>" . 
  "<input name=\"MSRP\" type=\"text\" class=\"form-control\" id=\"inputAddress\" value=\"$product->MSRP\" placeholder=\"MSRP\"></div>";
  
  echo "<input id=\"formSubmit\"type=\"submit\">" . "</form>";
?>

<script>

async function submitForm(data)
{
  let yurl = new URL('http://127.0.0.1:8000/products/editProduct');

  yurl.pathname = yurl.pathname + '/' + data.get('productCode');

  let promise = await fetch(yurl,
                            {
                              method: 'POST',
                              body: data,
                            });
                            
  let response = await promise.json();
  
  return response;
}


let form = document.querySelector('#editForm');

form.addEventListener('submit', function(){

  event.preventDefault();
  
  let children = form.elements;
  
  let payload = new FormData();
  
  for(let key of children)
  {
    if( key.name !== '')
    {
      payload.append(key.name, key.value);
    }
  }
  
  submitForm(payload).then( data => {
    console.log(data);
    
    document.querySelector('#editForm').innerText = data['message'];
  })

});
</script>
