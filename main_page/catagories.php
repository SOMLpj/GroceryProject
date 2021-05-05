
 <!-- establish connection -->
 <?php
$conn = mysqli_connect("sql3.freesqldatabase.com:3306", "sql3402886", "gn4yJmWUfg", "sql3402886");
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>

<!-- start session, get session user id -->
<?php
session_start();

$_SESSION["customer_id"] = 1;
$customer_id = $_SESSION["customer_id"];
$_SESSION["shopping_cart"] = 1;
echo "user id: " . $customer_id;
?>


<!-- create items in cart variable -->
<?php
$sql = "SELECT cart_id FROM shopping_cart WHERE FK_customer_id = $customer_id";
$result = mysqli_query($conn, $sql);
$cart_id = mysqli_fetch_assoc($result)['cart_id'];
echo "    cart id: " . $cart_id;

$sql = "SELECT COUNT(*) FROM item_in_cart WHERE FK_cart_id = $cart_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$cart_quantity = $row[0];
echo "    items in cart: " . $cart_quantity;
?>

<html>
  <head>
    <title>Catalog View</title>
    <link rel="stylesheet" href="catagories.css">
    <!-- <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script> -->
    <!-- <script src = "addToCart.js"></script> -->
  </head>

  <body>
    <div class ="menu">
      <div class = "wrapper">
        <ul>
          <li><a href="#">User Center</a></li>
          <li><a href="#">Log Out</a></li>
         <li><a href="#">Support</a></li>
        </ul>
        <form class ="search">
            <input type="text" name="search" placeholder="Search..">
        </form>
        <div id ="cart">
          <img src="images/cart.png" style="float:left;width:40px;height:40px;">
          <a href="#">(<?php echo $cart_quantity?>)</a>
        </div>
      </div>
    </div>

    <div class = "catagories">
      <div class = "wrapper">
        <ul>
          <li><a href="#">Featured</a></li>
          <?php
            // create SQL Query to display catagories
            $sql = "SELECT * FROM category";
            // execute the query
            $result = mysqli_query($conn, $sql);
            $current_category = 1;
              while ($row=mysqli_fetch_assoc($result))
              {
                $category = $row['category_name'];
                $category_number = $row['category_id'];
                ?>
                 <!-- <?php
                      if(isset($_POST[$category_number])) {
                        $current_category = $_POST[$category_number];
                        echo "$current_category found";
                      }
                      ?> -->
                   <li><a href="#"><?php echo ucfirst($category)?>s</a></li>
                    <!-- <form method="post">
                      <input type="submit" name="<?php echo $category_number?>" value="<?php echo $category_number?>">

                    </form> -->


                  <?php
              }
           ?>
        </ul>
      </div>
    </div>

    <div class = "items">
      <div class = "wrapper">
          <ul>
            <?php
              // select product details from product table where the category is 1 (fruit)
              // if(isset($_POST[$product_id]))
              // $catagory = 1;
              $sql = "SELECT product_id, product_name, price, weight, stock FROM product";
              // execute the query
              $result = mysqli_query($conn, $sql);

              // for each row in this query:
              while ($row=mysqli_fetch_assoc($result)) {
                // get the name, price, weight, stock, and id
                $name = $row['product_name'];
                $price = $row['price'];
                $weight = $row['weight'];
                $stock = $row['stock'];
                $product_id = $row['product_id'];
              ?>

              <?php
                // this entire php code is for handling adding to cart. if add to cart button (below) is clicked, upon page redirect,
                // post[current product_id] will be set, meaning that this product has been added to cart
                if(isset($_POST[$product_id])) {
                    // get the items in item cart where quantity is > 0
                    // cart id and product id should match according to the item added to cart, which are fetched
                    // using a post form from the add to cart button click
                    $sql2 = "SELECT * FROM item_in_cart WHERE FK_cart_id = $cart_id AND FK_product_id = $product_id AND quantity > 0";
                    $result2 = mysqli_query($conn, $sql2);
                    // if such row does not exist (mysqli_num_rows($result2) == 0), this means that the item
                    // does not already exist in the cart, so a new cart item must be created with quantity  1
                    if (mysqli_num_rows($result2) == 0) {
                      $sql2 = "INSERT INTO item_in_cart VALUES (0, $cart_id, $product_id, 1)";
                      $result2 = mysqli_query($conn, $sql2);
                    } else {
                      // add item to cart (quantity -> quantity + 1)
                      $sql2 = "UPDATE item_in_cart SET quantity = quantity + 1 WHERE FK_cart_id = $cart_id AND FK_product_id = $product_id";
                      $result2 = mysqli_query($conn, $sql2);
                    }
                }
              ?>






              <li>
                <img src="images/banana.jpeg">
                <div class="description">
                  Price: $<?php echo $price?> &nbsp
                  Weight: <?php echo $weight?> lbs &nbsp
                  Stock:  <?php echo $stock?>
                <div class="button">
                <!-- button form that posts the product id of the selected item -->
                  <form method="post">
                    <input type="submit" name="<?php echo $product_id?>" value="Add">
                  </form>
                  <!-- <div class="button1">
                    <button class="add" id="add-button" data-id="<?php echo $price?>">Add</button>
                  </div> -->
                </div>
                </div>
              </li>
            <?php
            }
            ?>
        </ul>
      </div>
    </div>

  </body>
</html>
