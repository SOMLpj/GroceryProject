<?php
// Show PHP errors
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once 'classes/product_class.php';

$objProduct = new Product();

// GET
if (isset($_GET['delete_id'])) {
  $id = $_GET['delete_id'];
  try {
    if ($id != null) {
      if ($objProduct->delete($id)) {
        $objProduct->redirect('productsInventory_view.php?deleted');
      }
    }
  } catch (PDOExxception $e) {
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
                    <h2 style="margin-top: 10px">Products Inventory</h2>


                    <?php
                      if (isset($_GET['updated'])) {
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>Product!</strong> Updated with success.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      } else if (isset($_GET['deleted'])) {
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>Product!</strong> Deleted with success.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      } else if (isset($_GET['inserted'])) {
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>Product!</strong> Inserted with success.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      } else if (isset($_GET['error'])) {
                        echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
                        <strong>DB Error!</strong> Something went wrong with your action. Try again!
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      }
                    ?>

                    <br>
                    <div class="table-responsive">
                      <table class="table table-striped table-sm">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Product Unit</th>
                            <th>Unit Price</th>
                            <th>Discount (%)</th>
                            <th>Product Weight</th>
                            <th>Pack Items</th>
                            <th>Shipping Weight</th>
                            <th>Product Description</th>
                            <th>Product Image</th>
                            <th>Product Stock</th>
                            <th>Product Category</th>
                            <th></th>
                          </tr>
                        </thead>
                        <?php
                          $query = "SELECT * FROM product";
                          $stmt = $objProduct->runQuery($query);
                          $stmt->execute();
                        ?>
                        <tbody>
                          <?php if ($stmt->rowCount() > 0) {
                            while ($rowProduct = $stmt->fetch(PDO::FETCH_ASSOC)) {
                           ?>
                           <tr>
                              <td><?php print($rowProduct['product_id']); ?></td>
                              <td>
                               <a href="edit_product_form.php?edit_id=<?php print($rowProduct['product_id']); ?>">
                                 <?php print($rowProduct['product_name']); ?>
                               </a>
                              </td>
                              <td><?php print($rowProduct['unit']); ?></td>
                              <td><?php print($rowProduct['price']); ?></td>
                              <td><?php print($rowProduct['discount']); ?></td>
                              <td><?php print($rowProduct['weight_per_item']); ?></td>
                              <td><?php print($rowProduct['item_per_pack']); ?></td>
                              <td><?php print($rowProduct['shipping_weight']); ?></td>
                              <td><?php print($rowProduct['description']); ?></td>
                              <td><img src="<?php echo $rowProduct['image']; ?>" style="width:80px;height:80px;"></td>
                              <td><?php print($rowProduct['stock']); ?></td>
                              <td><?php print($rowProduct['FK_category_id']); ?></td>
                              <td>
                               <a class="confirmation" href="productsInventory_view.php?delete_id=<?php print($rowProduct['product_id']); ?>">
                                 <span data-feather="trash"></span>
                               </a>
                              </td>
                           </tr>
                          <?php } } ?>
                        </tbody>
                      </table>

                    </div>

                </main>
            </div>
        </div>
        <!-- Footer scripts, and functions -->
        <?php require_once 'includes/footer.php'; ?>

        <!-- Custom scripts -->
        <script>
            // JQuery confirmation
            $('.confirmation').on('click', function () {
                return confirm('Are you sure you want do delete this product?');
            });
        </script>
    </body>
</html>
