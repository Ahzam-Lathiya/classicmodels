<?php
use app\core\Application;

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title><?php echo $this->title; ?></title>
  </head>
  <body>
  
<! -- NAVBAR BELOW -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">♠️</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link" href="/">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/about">About</a>
        </li>

        <?php 
              //if( isset($_SESSION['user']) ):
              if( !Application::$app->isGuest() ):
        ?>  
        
        <li class="nav-item">
          <a class="nav-link" href="/secret1">Secret1</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="/orders">Orders</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="/products">Products</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/customers">Customers</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/productLines">Product Lines</a>
        </li>

        <?php endif; ?>
      

      </ul>

      <ul class="navbar-nav ml-auto mb-2 mb-lg-0">


        <?php 
              //if( !isset($_SESSION['user']) ):
              if( Application::$app->isGuest() ):
        ?>
        
        <li class="nav-item">
          <a class="nav-link" href="/login">Login</a>
        </li>
        
        
        <?php else: ?>

        <li class="nav-item">
          <a class="nav-link" href="/addUser">Add User</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="/profile"><?php echo Application::$app->user->getFullName(); ?></a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="/logout">Logout</a>
        </li>
        <?php endif; ?>

      </ul>


    </div>
  </div>
</nav>

<! -- NAVBAR ABOVE -->

<div class="container">
  {{ content }}
</div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    -->
  </body>
</html>

