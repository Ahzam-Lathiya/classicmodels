<style>
table, th, td {
  border: 1px solid black;
}
</style>

<h2>All Product Lines</h2>
<?php 
$lines = $productLines->fetchProductLines();
$count = count($lines);

echo "<p id=\"countHolder\">No. of ProductLines:$count</p>";
 ?>

<a class="nav-link" href="/productLines/addProductLine">+ Create New Product Line</a>
<?php

$this->title = 'Product Lines';

echo '<table>';
echo '<tbody>';

echo '<tr>';

echo '<th>Product Line</th>';
echo '<th>Text Description</th>';
echo '<th>HTML</th>';
echo '<th>Image</th>';

echo '</tr>';


for($i=0; $i < $count; $i++)
{
  echo '<tr>';
  
  foreach($lines[$i] as $key => $value)
  {
    echo '<td>' . $value . '</td>';
  }
  
  echo '</tr>';
}


echo '</table>';
echo '</tbody>';

?>
