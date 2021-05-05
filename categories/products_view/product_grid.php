<?php
    if(isset($_POST['product-to-add'])) {
      $product_id = $_POST['product-to-add'];
      add_one_to_cart($product_id, $user_id);
    }
 ?>

<div class="container">    
<?php
// for loop
for($row = 0; $row < count($products) / 3; $row++) {
?>

<div class="container">    
  <div class="row">
    <?php
    for ($index = $row * 3; $index < $row * 3 + 3 && $index != count($products); $index++) {
        $name = $products[$index]['product_name'];
        $price = $products[$index]['price'];
        $weight = $products[$index]['shipping_weight'];
        $stock = $products[$index]['stock'];
        $product_id = $products[$index]['product_id'];
    ?>
    <div class="col-sm-4">
      <div class="panel panel-success">
        <div class="panel-heading"><?php echo($name)?></div>
        <div class="panel-body">
          <form method="GET" action="product_view.php" class="form-inline">
            <br>
            <input type="hidden" value="<?php echo($name)?>" name="product">
            <input type=image src="../resource/banana.jpeg" style="width:100%" alt="Image"></button>
          </form>
        </div>
        <div class="panel-footer text-center">
            Price: $<?php echo $price?> &nbsp <br> 
            Weight: <?php echo $weight?> lbs &nbsp <br> 
            Stock: <?php echo $stock?> <br>
            <form method="POST">
              <input type="hidden" value="<?php echo($product_id)?>" name="product-to-add">
              <input class="btn btn-success" type="submit" value="Add">
            </form>
        </div>
      </div>
    </div>
    <?php
    }
    ?>
  </div>
</div><br>

<?
}
?>

<footer class="container-fluid text-center">  
<h1>Spartan Fresh</h1>
</footer>

</body>
</html>
