<?php

$this->title = 'Create Product Line';
?>

<form id="createForm" action="/productLines/addProductLine" method="POST">

  <div class="form-row">
  
  <div class="form-group col-md-5">
    <label>Product Line Name</label>
    <input name="productLine" type="text" class="form-control" id="inputAddress" placeholder="productLine name">
  </div>
  
  <div class="form-group col-md-5">
    <label>Text Description</label>
    <input name="textDescription" type="text" class="form-control" id="inputAddress" placeholder="description">
  </div>
    
  
  <div class="form-group col-md-5">
    <label>HTML Description</label>
    <input name="htmlDescription" type="text" class="form-control" id="inputAddress" placeholder="html description">
  </div>

  <div class="form-group col-md-5">
    <label>Image</label>
    <input name="image" type="text" class="form-control" id="inputAddress" placeholder="image">
  </div>

  <input type="submit">
  
</form>

<script>

async function submitForm(data)
{
  let yurl = new URL('http://127.0.0.1:8000/productLines/addProductLine');

  yurl.pathname = yurl.pathname;

  let promise = await fetch(yurl,
                            {
                              method: 'POST',
                              body: data,
                            });
                            
  let response = await promise.json();
  
  return response;
}


let form = document.querySelector('#createForm');

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
    
    document.querySelector('#createForm').innerText = data['message'];
  })

});

</script>
