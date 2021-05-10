<?php
// Show PHP errors
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once 'classes/product_class.php';

$objProduct = new Product();

// GET
if (isset($_GET['edit_id'])) {
  $product_id = $_GET['edit_id'];
  $stmt = $objProduct->runQuery("SELECT * FROM product WHERE product_id=:product_id");
  $stmt->execute(array(":product_id" => $product_id));
  $rowProduct = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
  $product_id = null;
  $rowProduct = null;
}

// POST
if (isset($_POST['btn_save'])) {
  $product_name     = strip_tags($_POST['product_name']);
  $price            = strip_tags($_POST['price']);
  $discount         = strip_tags($_POST['discount']);
  $unit             = strip_tags($_POST['unit']);
  $weight_per_item  = strip_tags($_POST['weight_per_item']);
  $item_per_pack    = strip_tags($_POST['item_per_pack']);
  $shipping_weight  = strip_tags($_POST['shipping_weight']);
  $description      = strip_tags($_POST['description']);
  $image            = strip_tags($_POST['image']);
  $stock            = strip_tags($_POST['stock']);
  $FK_category_id   = strip_tags($_POST['FK_category_id']);

  try {
    if ($product_id != null) {
      if ($objProduct->update($product_name, $price, $discount, $unit, $weight_per_item, $item_per_pack, $shipping_weight, $description, $image, $stock, $FK_category_id, $product_id)) {
        $objProduct->redirect('productsInventory_view.php?updated');
      }
    } else {
      if ($objProduct->insert($product_name, $price, $discount, $unit, $weight_per_item, $item_per_pack, $shipping_weight, $description, $image, $stock, $FK_category_id)) {
        $objProduct->redirect('productsInventory_view.php?inserted');
      } else {
        $objProduct->redirect('productsInventory_view.php?error');
      }
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Head metas, css, and title -->
        <?php require_once 'includes/head.php'; ?>
    </head>
    <body>
        <!-- Header banner -->
        <?php require_once 'includes/header.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar menu -->
                <?php require_once 'includes/sidebar.php'; ?>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                  <h1 style="margin-top: 10px">Add New Product</h1>
                  <p>Required fields are in (*)</p>
                  <form method="post">
                    <div class="form-group">
                      <label for="product_id">Product ID</label>
                      <input class="form-control" type="text" name="product_id" id="product_id" placeholder="Assigned by the System" value="" readonly>
                    </div>
                    <div class="form-group">
                      <label for="product_name">Product Name*</label>
                      <input class="form-control" type="text" name="product_name" id="product_name" placeholder="Enter Product Name" value="" required maxlength="100">
                    </div>
                    <div class="form-group">
                      <label for="unit">Product Unit*</label>
                      <input class="form-control" type="text" name="unit" id="unit" placeholder="Enter Product Unit" value="" required maxlength="100">
                    </div>
                    <div class="form-group">
                      <label for="price">Unit Price*</label>
                      <input class="form-control" type="text" name="price" id="price" placeholder="Enter Product Price" value="" required maxlength="100">
                    </div>
                    <div class="form-group">
                      <label for="discount">Discount (%)*</label>
                      <input class="form-control" type="text" name="discount" id="discount" placeholder="Enter Product Discount" value="" required maxlength="100">
                    </div>
                    <div class="form-group">
                      <label for="weight_per_item">Item's Weight (Lb)*</label>
                      <input class="form-control" type="text" name="weight_per_item" id="weight_per_item" placeholder="Enter Item's Weight" value="" required>
                    </div>
                    <div class="form-group">
                      <label for="item_per_pack">Items per Pack *</label>
                      <input class="form-control" type="text" name="item_per_pack" id="item_per_pack" placeholder="Enter Items per Pack" value="" required>
                    </div>
                    <div class="form-group">
                      <label for="shipping_weight">Shipping Weight (Lb) *</label>
                      <input class="form-control" type="text" name="shipping_weight" id="shipping_weight" placeholder="Enter Total Shipping Weight" value="">
                    </div>
                    <div class="form-group">
                      <label for="description">Description*</label>
                      <input class="form-control" type="text" name="description" id="description" placeholder="Enter Product Description" value="" required>
                    </div>
                    <div class="form-group">
                      <label for="image">Image</label>
                      <input class="form-control" type="text" name="image" id="image"  placeholder="Enter Product Image" value="">
                    </div>
                    <div class="form-group">
                      <label for="stock">Stock*</label>
                      <input class="form-control" type="text" name="stock" id="stock" placeholder="Enter Product Stock" value="" required>
                    </div>
                    <div class="form-group">
                      <label for="FK_category_id">Product Category ID*</label>
                      <!-- <input class="form-control" type="text" name="FK_category_id" id="FK_category_id" placeholder="Enter Product Category ID" value=""> -->
                      <select class="form-control" name="FK_category_id" id="FK_category_id" required>
                        <option value="" disabled selected hidden>Choose Category</option>
                        <option value="1">1 - Fruit</option>
                        <option value="2">2 - Vegetable</option>
                        <option value="3">3 - Protein</option>
                        <option value="4">4 - Dairy</option>
                        <option value="5">5 - Baked Goods</option>
                        <option value="6">6 - Snack</option>
                      </select>
                    </div>
                    <input class="btn btn-primary mb-2" type="submit" name="btn_save" value="Save">
                    <a class="btn btn-danger mb-2" href="productsInventory_view.php" role="button">Cancel</a>
                  </form>
                </main>
            </div>
        </div>
        <!-- Footer scripts, and functions -->
        <?php require_once 'includes/footer.php'; ?>
    </body>
</html>
