<?php
//'this' is an instance of the app\core\View class

$this->title = 'Profile';

?>
<button id="close">Stop Listening</button>
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

let evtSource = new EventSource('http://127.0.0.1:8000/push');
console.log(evtSource.withCredentials);
console.log(evtSource.readyState);
console.log(evtSource.url);

evtSource.onopen = function(){
  console.log('Listening for events..');
}

evtSource.onmessage = function(e){
  console.log(e.data);
}

evtSource.onerror = function(){
  console.log('EventSource Failed');
}

window.addEventListener('popState', function(){
  console.log('Connection closed');
  evtSource.close();
});

document.querySelector('#close').addEventListener('click', function()
{
  console.log('Connection closed');
  evtSource.close();
});

</script>
