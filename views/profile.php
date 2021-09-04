<?php
//'this' is an instance of the app\core\View class

$this->title = 'Profile';

?>
<div>
<ul>

<?php 

foreach($employee as $key => $value)
{
  if($key != 'password')
  {
    echo "<li>$key: $value </li>";
  }
}

?>

</ul>

<?php 
if( $message )
{
  echo "<h2>" . $message;
  return ;
}
?>

<form method="POST" action="/editPass">

  <h3>Change Password: </h3>

  <div class="form-group">
    <label for="exampleInputPassword1">Enter New Password</label>
    <input name="password" type="password" class="form-control" id="passwordChange" placeholder="Password" required>
  </div>
  
  <br>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Confirm Password</label>
    <input name="passwordChange" type="password" class="form-control" id="passwordChangeConfirm" placeholder="Password" required>
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>

<script>

</script>
