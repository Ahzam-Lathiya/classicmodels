<?php

$this->title = 'Orders';

?>
<style>
table, th, td {
  border: 1px solid black;
}
</style>


<h2>All Orders of the Company</h2>

<?php
echo '<select id="statusDrop" >';
echo '<option value="all">All</option>';

foreach($statusTypes as $type)
{
  echo "<option value='$type[0]'>" . $type[0] . "</option>";
}

echo '</select>';

echo "<p id=\"countHolder\">Orders fetched:$count</p>";

?>

<table style="width:100%">
<tbody id="ordersTable">

<tr>
<th>#</th>
<th>Order Number</th>
<th>Order Date</th>
<th>Required Date</th>
<th>Shipped Date</th>
<th>Status</th>
<th>Comments</th>
<th>Customer Number</th>

</tr>


<?php

for($i=0; $i<count($allOrders); $i++)
{
  echo "<tr>";
  
  echo '<td>' . $i +1 . '</td>';
  echo '<td>' . "<a href=orders/order/" . $allOrders[$i]['orderNumber'] . " target='/blank'>" . $allOrders[$i]["orderNumber"] . '</a>' . '</td>';
  echo "<td>" . $allOrders[$i]['orderDate'] . "</td>";
  echo "<td>" . $allOrders[$i]['requiredDate'] . "</td>";
  echo "<td>" . $allOrders[$i]['shippedDate'] . "</td>";
  echo "<td>" . $allOrders[$i]['status'] . "</td>";
  echo "<td>" . $allOrders[$i]['comments'] . "</td>";
  echo "<td>" . $allOrders[$i]['customerNumber'] . "</td>";
  
  echo "</tr>";
}
?>

</tbody>
</table>

<script>

async function fetchWithURLEncode(payload)
{
    let arr = [];
    
    console.log("URLEncoding");
    
    for( let key in payload )
    {
      let str = encodeURIComponent(key) + "=" + encodeURIComponent( payload[key] );
      arr.push(str);
    }
    
    arr = arr.join("&");
    
    console.log(typeof(arr) );
    
    let yurl = new URL('http://127.0.0.1:8000/admin/getStatus');

    let response = await fetch(yurl, {
      method: "POST",
      headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"},
      body: arr,
    });
    
    let promise = await response.json();
    
    return promise;
}


async function fetchStuff(value)
{
  let yurl = new URL('http://127.0.0.1:8000/admin/getStatus');
  
  let form = new FormData();
  
  form.append('choice', value);
          
  let response = await fetch(yurl, 
					{
						method: 'POST',
						//headers: {'Content-Type': 'multipart/formdata'},
						body: form,
					});
				
  let back_data = await response.json();
			
  return back_data;
}


document.querySelector('#statusDrop').addEventListener('change', function(){

 fetchWithURLEncode( {'choice': event.target.value} ).then( function(some_data){
 //fetchStuff(event.target.value).then( some_data => { 
     //console.log(some_data);
     
     let tableBody = document.querySelector('#ordersTable');
     let headRow = tableBody.firstElementChild;
     
     let currentRow = headRow.nextElementSibling;

     //iterate through the table to delete till the last row
     while( currentRow.nextElementSibling != null)
     {
       let nextRow = currentRow.nextElementSibling;
       currentRow.parentNode.removeChild(currentRow);
            
       currentRow = nextRow;
     }
     
     //finally delete the last row
     currentRow.parentNode.removeChild(currentRow);

     document.querySelector('#countHolder').innerText = 'Orders fetched:' + some_data.length;
     
     for(let i=0; i<some_data.length ;i++)
     {
       let tr = document.createElement('tr');
       
       let td = document.createElement('td');
       td.innerText = i + 1;
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td = "<a href=orders/order/" + some_data[i]['orderNumber'] + " target='/blank'>" + some_data[i]['orderNumber'] + "</a>";
       
       tr.insertAdjacentHTML('beforeend', td);
       
       td = document.createElement('td');
       td.innerText = some_data[i]['orderDate'];
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td.innerText = some_data[i]['requiredDate'];
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td.innerText = some_data[i]['shippedDate'];
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td.innerText = some_data[i]['status'];
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td.innerText = some_data[i]['comments'];
       
       tr.appendChild(td);
       
       td = document.createElement('td');
       td.innerText = some_data[i]['customerNumber'];
       
       tr.appendChild(td);              
              
                     
       tableBody.appendChild(tr);
     }
     

 });

})

</script>
