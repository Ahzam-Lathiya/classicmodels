<?php

$this->title = $orderID;

?>

<style>
table, th, td {
  border: 1px solid black;
}
</style>

<table style="width:100%">
<tbody id="productsTable">

<tr>
<th>#</th>
<th>Product Code</th>
<th>Product Name</th>
<th>Quantity ordered</th>
<th>Price each</th>
</tr>

<?php

for($i=0; $i<count($orderDetails); $i++)
{
  echo '<tr>';
  
  echo '<td>' . $i + 1 . '</td>';
  echo '<td>' . "<a href=/products/product/" . $orderDetails[$i]['productCode'] . " target='/blank'>" . $orderDetails[$i]["productCode"] . '</a>' . '</td>';
  echo '<td>' . $orderDetails[$i]['productName'] . '</td>';
  echo '<td>' . $orderDetails[$i]['quantityOrdered'] ?? 'Total:' . '</td>';
  echo '<td>' . $orderDetails[$i]['priceEach'] . '</td>';
  
  echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '<ul>';

foreach($order as $key => $value)
{
  if($key === 'customerNumber')
  {
    echo '<li>' . $key . " : " . "<a href=/customers/customer/" . $value . " target='/blank'>" . $value . '</a>' . '</li>';
  }
  
  else
  {
    echo '<li>' . $key . " : " . $value . '</li>';
  }
}

echo '</ul>';
?>
