<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="category_view.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
      margin-bottom: 0;
    }
   
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  </style>
</head>
<body>

<div class="jumbotron">
  <div class="container text-center">
    <h1>Spartan Fresh</h1>      
    <p>"Best in town!"</p>
  </div>
</div>

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li> 
          <form method="GET" action="search_view.php" class="form-inline">
            <br>
            <input type="text" class="form-control" size="30" name="search" onpaste="return false;" ondrop="return false;" autocomplete="off" placeholder="Enter keyword..">
            <input type="submit" class="btn btn-danger" value="Search"></button>
          </form>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo ($first_name)?></a></li>
        <li><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Cart (<?php echo ($item_in_cart_count)?>)</a></li>
      </ul>
    </div>
  </div>
</nav>