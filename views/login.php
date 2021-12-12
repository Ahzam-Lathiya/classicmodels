<?php

$this->title = 'Login';
?>

<h1>Login</h1>

<form id="logForm" action="" method="post">

  <div class="mb-3">
    <label>Employee ID</label>
    <input type="text" name="emp_ID" class="form-control">
  </div>

  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control">
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
  <p class="messageArea"></p>

</form>

<script>

async function submitForm(data)
{
  let yurl = new URL('http://127.0.0.1:8000/login');

  let statusMessages = {401: 'Password incorrect for user',
                        403: 'This ID is already logged in another session',
                        404: 'User ID doesn\'t exist'
                       };

  let promise = await fetch(yurl,
                            {
                              method: 'POST',
                              body: data,
                            });
                            
  let response = await promise.json();
  
  //if no exception is raised in promise
  
  if(promise.ok)
  {
    return response;
  }
  
  else
  {
    let message = statusMessages[promise.status];
    throw new Error(message);
  }

}


let form = document.querySelector('#logForm');

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
    
    document.querySelector('.messageArea').innerText = data['message'];
    
    location.href = '/';
    
  }).catch( error => {
      console.log(error);
    
      document.querySelector('.messageArea').innerText = error.message;
    });

  });

</script>
