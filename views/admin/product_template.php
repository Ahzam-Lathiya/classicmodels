<?php

$this->title = $product->productName;

?>

<ul id="productDetails">
<?php

foreach($product as $key => $value)
{
  echo '<li>' . $key . " : " . $value . '</li>';
}

?>
</ul>

<!--<button href="/products/editProduct" target="\blank">Edit this Product</a>-->

<button id="editProductButton">Edit this Product</button>

<script>

async function followPage()
{
  let promise = await fetch( 'http:127.0.0.1:8000/products/editProduct', {
                              method: 'GET',
			                  headers: {'Content-Type': 'application/json'},
  });
  
  let response = await promise.json();
  
  return response;
}

document.querySelector('#editProductButton').addEventListener('click', function(){
  let prodID = document.querySelector('#productDetails').firstElementChild.innerText.split(":")[1];
  
  prodID = prodID.trimLeft();
  
  location.href = '/products/editProduct/' + prodID;
});

</script>
