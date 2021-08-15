<?php

$this->title = 'Products';

?>
<style>
table, th, td {
  border: 1px solid black;
}
</style>

<form id="searchForm">

  <input type="text" placeholder="Search for customer..." required></input>
  <button type="submit">Search</button>
  
</form>

<h2>All Customers.</h2>

<table style="width:100%">
<tbody id="productsTable">

<tr>
<th>#</th>
<th>customerNumber</th>
<th>customerName</th>
<th>contactLastName</th>
<th>contactFirstName</th>
<th>phone</th>
<th>addressLine1</th>
<th>addressLine2</th>
<th>city</th>
<th>state</th>
<th>postalCode</th>
<th>country</th>
<th>salesRepEmployeeNumber</th>
<th>creditLimit</th>
</tr>


<?php

for($i=0; $i<count($allCustomers); $i++)
{
  echo "<tr>";
  
  echo '<td>' . $i +1 . '</td>';
  echo '<td>' . "<a href=/customers/customer/" . $allCustomers[$i]['customerNumber'] . " target='/blank'>" . $allCustomers[$i]["customerNumber"] . '</a>' . '</td>';
  echo "<td>" . $allCustomers[$i]['customerName'] . "</td>";
  echo "<td>" . $allCustomers[$i]['contactLastName'] . "</td>";
  echo "<td>" . $allCustomers[$i]['contactFirstName'] . "</td>";
  echo "<td>" . $allCustomers[$i]['phone'] . "</td>";
  echo "<td>" . $allCustomers[$i]['addressLine1'] . "</td>";
  echo "<td>" . $allCustomers[$i]['addressLine2'] . "</td>";
  echo "<td>" . $allCustomers[$i]['city'] . "</td>";
  echo "<td>" . $allCustomers[$i]['state'] . "</td>";
  echo "<td>" . $allCustomers[$i]['postalCode'] . "</td>";
  echo "<td>" . $allCustomers[$i]['country'] . "</td>";
  echo "<td>" . $allCustomers[$i]['salesRepEmployeeNumber'] . "</td>";
  echo "<td>" . $allCustomers[$i]['creditLimit'] . "</td>";
  
  echo "</tr>";
}
?>

</tbody>
</table>

<script>

let productNames = [];

async function fetchStuff()
{
  let yurl = new URL('http://127.0.0.1:8000/getProductNames');
  
  let promise = await fetch( yurl, {
                              method: 'GET',
						      headers: {'Content-Type': 'application/json'},
  });
  
  let response = await promise.json();
  
  return response;
}


function deleteTable()
{
  let tableBody = document.querySelector('#productsTable');
  let headRow = tableBody.firstElementChild;
  
  let currentRow = headRow.nextElementSibling;
  
  if(currentRow)
  {
  while(currentRow.nextElementSibling !== null)
  {
    let nextRow = currentRow.nextElementSibling;
    currentRow.parentNode.removeChild(currentRow);
    
    currentRow = nextRow;
  }
  
  //finally delete the last row
  currentRow.parentNode.removeChild(currentRow);
  }
  return;
}


function populateTable(data)
{
  //document.querySelector('#countHolder').innerText = 'Orders fetched:' + data.length;
  let tableBody = document.querySelector('#productsTable');  
  
  document.querySelector('#countHolder').innerText = 'No. of Products:' + data.length;
  
  for(let i=0; i<data.length; i++)
  {
       let tr = document.createElement('tr');
       
       let td = document.createElement('td');
       td.innerText = i + 1;
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td = "<a href=products/product/" + data[i]['productCode'] + " target='/blank'>" + data[i]['productCode'] + "</a>";
       
       tr.insertAdjacentHTML('beforeend', td);

       td = document.createElement('td');
       td.innerText = data[i]['productName'];
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td.innerText = data[i]['productLine'];
       
       tr.appendChild(td);

       td = document.createElement('td');
       td.innerText = data[i]['productScale'];
       
       tr.appendChild(td);

       td = document.createElement('td');
       td.innerText = data[i]['productVendor'];
       
       tr.appendChild(td);

       td = document.createElement('td');
       td.innerText = data[i]['quantityInStock'];
       
       tr.appendChild(td);

       td = document.createElement('td');       
       td.innerText = data[i]['buyPrice'];
       
       tr.appendChild(td);

       td = document.createElement('td');       
       td.innerText = data[i]['MSRP'];
       
       tr.appendChild(td);
                     
       tableBody.appendChild(tr);
  }
  
  return;
}

function searchName(arr, name)
{
  let re = new RegExp(name, 'i');
  let data = [];

  for(let i=0; i<arr.length; i++)
  {
    let result = re.exec(arr[i]['productName']);
    if( result)
    {
      data.push( arr[i] );
    }
  }
  
  deleteTable();
  populateTable(data);
  return;
}

let form = document.querySelector('#searchForm');

let inputField = form.firstElementChild;

form.addEventListener('submit', function(){
  event.preventDefault();
  
  if( productNames.length === 0 )
  {
    fetchStuff().then( some_data => {
      productNames = some_data;
      
      searchName( productNames, inputField.value);
    });
  }
  
  searchName( productNames, inputField.value);

});

</script>
