<?php 
session_start();
include 'config.php';

// Get all dairy items
$stmt = $pdo->prepare("SELECT * FROM product WHERE FK_category_id = 4");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once "header.php";

?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-center">
        <li><a href="all_category_view.php">All</a></li>
        <li><a href="fruit_view.php">Fruit</a></li>
        <li><a href="vegetable_view.php">Vegetable</a></li>
        <li><a href="protein_view.php">Protein</a></li>
        <li class="active"><a href="dairy_view.php">Dairy</a></li>
        <li><a href="baked_goods_view.php">Baked Goods</a></li>
        <li><a href="sweets_view.php">Sweets</a></li>

    </div>
  </div>
</nav>

<?php
include_once "product_grid.php";
?>