<?php 
  if(!isset($_SESSION)) {
    //start session
    session_start();
  }
  if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false) {
    echo "<script>notLoginIn()</script>";
  }
  //Get customer id
  $customerId = $_SESSION['user_id'];
  //create connection
  $conn = mysqli_connect("sql3.freesqldatabase.com:3306", "sql3402886", "gn4yJmWUfg", "sql3402886");
  //check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
?>
<html>
  <head>
    <title>Order History</title>
    <style>
      <?php include "order_history.css" ?>
    </style>
  </head>
  <?php include_once '../component/head_nav/head_nav.php'; ?>
  <body>
    <br><br>
    <h1> Order History</h1>
    <div class="flex-container">
    <?php
      
      $sql = "SELECT order_date, order_total, order_id FROM customer_order WHERE FK_customer_id='$customerId'";
      $results = mysqli_query($conn, $sql);
      if (mysqli_num_rows($results) === 0) {
        echo "<h3>You have no order before. Start Your First Order!</h3><br>";
        echo "<button>Continue Shopping</button>";
        exit("");
      }
      
      while ($row=mysqli_fetch_assoc($results)) {
        $order_date = $row['order_date'];
        $order_total = $row['order_total'];
        $order_id = $row['order_id'];
        $items_in_order = array();
        $sql1 = "SELECT FK_product_id, quantity FROM order_item WHERE FK_order_id='$order_id'";
        $results1 = mysqli_query($conn, $sql1);
        while ($row=mysqli_fetch_assoc($results1)){
          $product_id = $row['FK_product_id'];
          $quantity = $row['quantity'];

          $sql2 = "SELECT product_name FROM product WHERE product_id='$product_id'";
          $results2 = mysqli_query($conn, $sql2);
          if ($row=mysqli_fetch_assoc($results2)) {
            $product_name = $row['product_name'];
            $product = array($product_name, $quantity);
          }
          array_push($items_in_order, $product);
        }
        ?>

        <div class="order_history">
          <table>
            <tr class="date">
              <td>Order Date: </td>
              <td><?php echo $order_date;?></td>
            </tr>
            <tr class="total">
              <td></td>
              <td>Total: $<?php echo $order_total;?></td>
            </tr>
            <tr>
              <th>Item</th>
              <th>quantity</th>
            </tr>
            <tr>
            <?php 
              for ($i = 0; $i < count($items_in_order); $i ++) {
                for ($j = 0; $j < 2; $j ++) {
                  ?>
                  <td><?php echo $items_in_order[$i][$j];?></td>
                  <?php
                }
                ?>
                </tr>
                
                <?php 
              }
              ?>
          </table>
        </div>
              <?php
      } 
      ?>
    </div>
    <div class="a">
    <a class="a" href="user_center.php">Go Back To User Center</a>
      </div>
  </body>
</html>