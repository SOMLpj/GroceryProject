<?php
// Show PHP errors
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once 'classes/product.php';

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
  $product_name   = strip_tags($_POST['product_name']);
  $price          = strip_tags($_POST['price']);
  $weight         = strip_tags($_POST['weight']);
  $description    = strip_tags($_POST['description']);
  $image          = strip_tags($_POST['image']);
  $stock          = strip_tags($_POST['stock']);
  $FK_category_id = strip_tags($_POST['FK_category_id']);

  try {
    if ($product_id != null) {
      if ($objProduct->update($product_name, $price, $weight, $description, $image, $stock, $FK_category_id, $product_id)) {
        $objProduct->redirect('index.php?updated');
      }
    } else {
      if ($objProduct->insert($product_name, $price, $weight, $description, $image, $stock, $FK_category_id)) {
        $objProduct->redirect('index.php?inserted');
      } else {
        $objProduct->redirect('index.php?error');
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
                  <h1 style="margin-top: 10px">Add / Edit Product</h1>
                  <p>Required fields are in (*)</p>
                  <form method="post">
                    <div class="form-group">
                      <label for="product_id">Product ID</label>
                      <input class="form-control" type="text" name="product_id" id="product_id" value="<?php print($rowProduct['product_id']); ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="product_name">Product Name*</label>
                      <input class="form-control" type="text" name="product_name" id="product_name" placeholder="Enter Product Name" value="<?php print($rowProduct['product_name']); ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                      <label for="price">Unit Price*</label>
                      <input class="form-control" type="text" name="price" id="price" placeholder="Enter Product Price" value="<?php print($rowProduct['price']); ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                      <label for="weight">Weight*</label>
                      <input class="form-control" type="text" name="weight" id="weight" placeholder="Enter Product Weight" value="<?php print($rowProduct['weight']); ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="description">Description*</label>
                      <input class="form-control" type="text" name="description" id="description" placeholder="Enter Product Description" value="<?php print($rowProduct['description']); ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="image">Image</label>
                      <input class="form-control" type="text" name="image" id="image"  placeholder="Enter Product Image" value="<?php print($rowProduct['image']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="stock">Stock*</label>
                      <input class="form-control" type="text" name="stock" id="stock" placeholder="Enter Product Stock" value="<?php print($rowProduct['stock']); ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="FK_category_id">Product Category ID*</label>
                      <input class="form-control" type="text" name="FK_category_id" id="FK_category_id" placeholder="Enter Product Category ID" value="<?php print($rowProduct['FK_category_id']); ?>">
                    </div>
                    <input class="btn btn-primary mb-2" type="submit" name="btn_save" value="Save">
                  </form>
                </main>
            </div>
        </div>
        <!-- Footer scripts, and functions -->
        <?php require_once 'includes/footer.php'; ?>
    </body>
</html>
