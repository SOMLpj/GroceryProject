<?php 
session_start();
include 'config.php';

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
        <li><a href="dairy_view.php">Dairy</a></li>
        <li><a href="baked_goods_view.php">Baked Goods</a></li>
        <li><a href="sweets_view.php">Sweets</a></li>
    </div>
  </div>
</nav>

<?php
if (isset($_GET['product'])) {
    $product_name = $_GET['product'];
    $stmt = $pdo->prepare("SELECT * FROM product WHERE product_name='$product_name'");
    $stmt->execute();
    $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $name = $product[0]['product_name'];
    $description = $product[0]['description'];
    $price = $product[0]['price'];
    $weight = $product[0]['shipping_weight'];
    $stock = $product[0]['stock'];
    $product_id = $product[0]['product_id'];

    $stmt = $pdo->prepare("SELECT quantity FROM item_in_cart WHERE FK_product_id=$product_id AND FK_customer_id=$user_id");
    $stmt->execute();
    $cart_quantity = $stmt->fetchColumn();

if (isset($_GET['quantity-to-add'])) {
    $add_result = $_GET['quantity-to-add'];
    $add_result_arr = explode(" ", "$add_result ");
    $id = $add_result_arr[0];
    $quantity_to_add = $add_result_arr[1]; 
    add_to_cart($id, $user_id, $quantity_to_add);
}
?>
<div class="container">    
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-success">
        <div class="panel-heading"><?php echo($name)?></div>
        <div class="panel-body"><img src="../resource/banana.jpeg" class="img-responsive" style="width:100%" alt="Image"></div>
        <div class="panel-footer"><?php echo($description)?></div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="row">
                    <form name="add_product" action="product_view.php">
                        <div class="col-md-2 col-md-offset-4">
                            <select class="form-control" name="quantity-to-add">
                                <?php
                                for ($i = 1; $i<=99; $i++) {
                                    if ($stock - $cart_quantity - $i > 0) {
                                ?>
                                <option value="<?php echo($product_id . " " . $i)?>"><?php echo($i) ?> &emsp;&ensp;($<?php echo($i * $price)?>)</option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="hidden" value="<?php echo($name)?>" name="product">
                            <input class="btn btn-success" type="submit" name="name" value="Add">
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div><br><br>
<?php
}
?>
