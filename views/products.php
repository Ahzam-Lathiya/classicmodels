<?php

$this->title = 'Products';

?>
<style>
table, th, td {
  border: 1px solid black;
}
</style>

<form id="searchForm">

  <input type="text" placeholder="Search for product..."></input>
  <button type="submit">Search</button>
  
</form>

<h2>All Products.</h2>
<?php echo "<p id=\"countHolder\">No. of Products:$count</p>"; ?>

<button id="close">Stop Listening</button>

<a class="nav-link" href="/products/addProduct" target="\blank">+ Add New Product</a>

<table style="width:100%">
<tbody id="productsTable">

<tr>
<th>#</th>
<th>Product Code</th>
<th>Product Name</th>
<th>Product Line</th>
<th>Product Scale</th>
<th>Product Vendor</th>
<th>Quantity In Stock</th>
<th>Buy Price($)</th>
<th>MSRP($)</th>
</tr>


<?php

for($i=0; $i<count($allProducts); $i++)
{
  echo "<tr>";
  
  echo '<td>' . $i + 1 . '</td>';
  echo '<td>' . "<a href=products/product/" . $allProducts[$i]['productCode'] . " target='/blank'>" . $allProducts[$i]["productCode"] . '</a>' . '</td>';
  echo "<td>" . $allProducts[$i]['productName'] . "</td>";
  echo "<td>" . $allProducts[$i]['productLine'] . "</td>";
  echo "<td>" . $allProducts[$i]['productScale'] . "</td>";
  echo "<td>" . $allProducts[$i]['productVendor'] . "</td>";
  echo "<td>" . $allProducts[$i]['quantityInStock'] . "</td>";
  echo "<td>" . $allProducts[$i]['buyPrice'] . "</td>";
  echo "<td>" . $allProducts[$i]['MSRP'] . "</td>";
  
  echo "</tr>";
}
?>

</tbody>
</table>

<script>
let productNames = <?php echo json_encode($allProducts); ?>;

let tableBody = document.querySelector('#productsTable');

let headRow = tableBody.firstElementChild;

function createRow(data, position)
{
  let tr = document.createElement('tr');
  
  let td = document.createElement('td');
  td.textContent = position + 1;
  
  tr.appendChild(td);
  
  td = document.createElement('td');
  let a = document.createElement('a');
  
  a.href = '/products/product/' + data['productCode'];
  a.target = "/blank";
  a.textContent = data['productCode'];
  
  td.appendChild(a);
  
  tr.appendChild(td);
  
  td = document.createElement('td');
  td.textContent = data['productName'];
  
  tr.appendChild(td);
  
  td = document.createElement('td');
  td.textContent = data['productLine'];
  
  tr.appendChild(td);

  td = document.createElement('td');
  td.textContent = data['productScale'];
       
  tr.appendChild(td);

  td = document.createElement('td');
  td.textContent = data['productVendor'];
       
  tr.appendChild(td);

  td = document.createElement('td');
  td.textContent = data['quantityInStock'];
       
  tr.appendChild(td);

  td = document.createElement('td');
  td.textContent = data['buyPrice'];
       
  tr.appendChild(td);

  td = document.createElement('td');
  td.textContent = data['MSRP'];
       
  tr.appendChild(td);
  
  return tr;
}


function separateProdID( prodID )
{
  let num = prodID.slice(1);
  
  let arr = num.split('_');
  
  return parseInt( arr.join('') );
}


function binarySearch(x)
{
  let left = 0;
  let right = productNames.length - 1;
  let num = separateProdID(x);
  
  if(num < separateProdID(productNames[0]['productCode']) )
  {
    return 0;
  }
  
  else if(num > separateProdID(productNames[right]['productCode']) )
  {
    return right + 1;
  }
  
  while(left <= right)
  {
    let mid = (left + right) / 2;
    mid = Math.floor(mid);
    let productCode = separateProdID( productNames[mid]['productCode'] );
	
    if(num === productCode )
    {
      return mid;
    }

    // if element is smaller than the element at mid
    else if(num < productCode )
    {
      // if element is bigger than the element behind mid
      if(num > separateProdID(productNames[mid - 1]['productCode']) )
      {
        return mid;
      }
      
      right = mid - 1;
    }

    // if element is bigger than the element at mid
    else if(num > productCode )
    {
      // if element is less than the element after mid
      if(num < separateProdID(productNames[mid + 1]['productCode']) )
      {
        return mid + 1;
      }
      
      left = mid + 1;
    }

  }
  
}


function editProducts( product )
{
  let prodID = product['productCode'];
  let position = binarySearch(prodID);
  
  productNames[position] = product;
  return position;
}


function addProduct( product )
{
  let prodID = product['productCode'];
  let position = binarySearch(prodID);
  
  //(position to insert, num of elements to remove, the data to be inserted)
  productNames.splice(position, 0, product);
  
  return position;
}


function binarySearchProducts(x)
{
  let left = 0;
  let right = productNames.length - 1;
  let num = separateProdID(x);
  
  while(left <= right)
  {
    let mid = (left + right) / 2;
    mid = Math.floor(mid);
	     
    let productCode = separateProdID( productNames[mid]['productCode'] );
    
    if(num === productCode)
    {
      return mid;
    }

    else if(num < productCode)
    {
      right = mid - 1;
    }

    else if(num > productCode)
    {
      left = mid + 1;
    }

  }
  
  return false;
}


function updateProductTable( product )
{
  let position = editProducts(product);

  let rows = tableBody.children;
  
  //update the row which is found with new data
  let row = createRow(product, position );

  rows[position + 1].replaceWith(row);

  return;
}



function appendProductRow( product )
{
  let position = addProduct(product);

  let rows = tableBody.children;
  
  let size = productNames.length;
  
  //if element is to be inserted at the end
  if( position === (size - 1) )
  {
    //update the row which is found with new data
    let row = createRow(product, position );

    document.querySelector('#countHolder').textContent = 'No. of Products:' + size;

    tableBody.appendChild(row);
    return;
  }
  
  //else if element is to be inserted somewhere IN BETWEEN OR STARTING
  
  //update the row which is found with new data
  let row = createRow(product, position );

  rows[position].insertAdjacentElement('afterend', row);
  
  //update the row number of rows below the new appended row
  while(rows[position].nextElementSibling != null)
  {
    position = position + 1;
    rows[position].firstElementChild.textContent = position;
  }

  document.querySelector('#countHolder').textContent = 'No. of Products:' + size;

  return;

}


async function fetchStuff()
{
  let yurl = new URL('http://127.0.0.1:8000/products/getProductNames');
  
  let promise = await fetch( yurl, {
                                    method: 'GET',
			                        headers: {'Content-Type': 'application/json'},
  });
  
  let response = await promise.json();
  
  return response;
}


function deleteTable()
{
  let currentRow = headRow.nextElementSibling;
  
  while(headRow.nextElementSibling !== null)
  {
    let nextRow = currentRow.nextElementSibling;
    currentRow.parentNode.removeChild(currentRow);
    
    currentRow = nextRow;
  }
  
  return;
}


function populateTable(data)
{
  document.querySelector('#countHolder').textContent = 'No. of Products:' + data.length;
  
  for(let i=0; i<data.length; i++)
  {
    let row = createRow(data[i], i);
    tableBody.appendChild(row);
  }
  
  return;
}


function searchName(name)
{
  // make a regex from string through RegExp() constructor. Here "name" is string
  // 'i' is for case insensitive.
  let re = new RegExp(name, 'i');
  let data = [];

  for(let i=0; i< productNames.length; i++)
  {
    // if the productName of record matches the regex
    if( re.exec(productNames[i]['productName']) )
    {
      //append the record to results array
      data.push( productNames[i] );
    }
  }
  
  //clear the entire table
  deleteTable();
  //populate the table with results
  populateTable(data);
  
  return;
}


function sleep(milliseconds)
{
  const date = Date.now();
  let currentDate = null;
  do 
  {
    currentDate = Date.now();
  }
  
  while (currentDate - date < milliseconds);
}


let form = document.querySelector('#searchForm');

let inputField = form.firstElementChild;

form.addEventListener('submit', function(){
  event.preventDefault();
  
  //if products array is empty
  if( productNames.length === 0 )
  {
    fetchStuff().then( some_data => {
      productNames = some_data;
      
      searchName( inputField.value);
    });
  }
  
  searchName( inputField.value);

});

let evtSource = new EventSource('http://127.0.0.1:8000/pusher');
console.log(evtSource.withCredentials);
console.log(evtSource.readyState);
console.log(evtSource.url);

evtSource.onopen = function(){
  console.log('Listening for events..');
}

evtSource.onmessage = function(e){
  console.log(e);
  
  if(e.data !== "[]")
  {
    let payload = JSON.parse(e.data);
    
    for(product of payload)
    {
      if(product['action'] === 'INSERT')
      {
        appendProductRow(product);
        alert("Product: " + product['productCode'] + " has been inserted.");
      }
    
      else
      {
        updateProductTable(product);
        alert("Product: " + product['productCode'] + " has been updated.");
      }
      
      //sleep(2500);
    }
    
    console.log("Nothing remaining in payload");
    return;
    //console.log('payload not empty');
  }
}

evtSource.onerror = function(){
  console.log('EventSource Failed');
}

document.querySelector('#close').addEventListener('click', function(){
  evtSource.close();
  console.log('Stopped Listening..');
});

</script>
