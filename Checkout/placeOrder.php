<?php
  //Start session
  session_start();
  //Get customer id
  $_SESSION['user_id'] = '1';
  $customerId = $_SESSION['user_id'];
  //Set dedault timezone
  date_default_timezone_set("America/Los_Angeles");
  //create connection
  $conn = mysqli_connect("sql3.freesqldatabase.com:3306", "sql3402886", "gn4yJmWUfg", "sql3402886");
  //check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  //Sales Tax rate in San Jose, 95112: 9.25%
  $salesTax = 0.0925;
 ?>

<html>
  <head>
    <title>Place Order</title>
  </head>
  <body>
    <?php
      $total = 0;
      $totalPrice = 0;
      $itemPrice = 0; //Without Tax
      $totalWeight = 0;
      $freeDeliveryWeight = 20; //In pounds
      $deliveryFee = 5;
      //Get every item id and quantity in the cart
      $sql = "SELECT FK_product_id, quantity FROM item_in_cart WHERE FK_customer_id='$customerId'";
      $results = mysqli_query($conn, $sql);
      $itemsInCart = array(); //Array for save item id and quantity
      $notEnoughStockItems = array(); //Array for save not enough stock item
      while ($row=mysqli_fetch_array($results)) {
        $orderProductId = $row['FK_product_id'];
        $quantity = $row['quantity'];

        //Get the item's stock from product database
        $sql1 = "SELECT stock, product_name, price, shipping_weight FROM product WHERE product_id='$orderProductId'";
        $results1 = mysqli_query($conn, $sql1);
        while ($row=mysqli_fetch_assoc($results1)){
          $stock = $row['stock'];
          $productName = $row['product_name'];
          $price = $row['price'];
          $shippingWeight = $row['shipping_weight'];
        }
        //If the item quantity in the cart is more than the avaliable stock,
        //this order should not be placed
        if ($quantity > $stock){
          array_push($notEnoughStockItems, $productName);
        }
        else {
          $itemsInCart[$orderProductId] = $quantity;
          //Make the array key = product_id, value = quantity

          $itemPrice += $price * $quantity;
          $totalWeight += $shippingWeight * $quantity;
        }
      }

      $total += $itemPrice;
      $total += $total * $salesTax;
      //Add delivery fee if total weigtht is over max free delivery weight
      if ($totalWeight >= $freeDeliveryWeight){
        $total += $deliveryFee;
      }
      $totalPrice = round("$total", 2); //Round to 2 decimal

      //If there is item that does not have enough stock, print it out
      if (!empty($notEnoughStockItems)){

        echo "Sorry, for ";
        foreach ($notEnoughStockItems as $value) {
           echo $value . ", ";
        }
        echo " we do not have enough stock for your order. Please <a href=\"checkout.php\">Go Back To Shopping Cart</a>.";

        exit(""); //Stop execute rest of the code, means don't place the order
      }

      if (isset($_POST["selectedPayment"]) && isset($_POST["selectedAddress"]) && isset($_POST["selectedDate"])){
        if ($_POST["selectedPayment"] && $_POST["selectedAddress"] && $_POST["selectedDate"]){
          $selectedPayment = $_POST["selectedPayment"];
          $payment_explode = explode('|', $selectedPayment);
          $selectedPayment = $payment_explode[0];
          $selectedPaymentId = $payment_explode[1];

          $selectedAddress = $_POST["selectedAddress"];
          $address_explode = explode('|', $selectedAddress);
          $selectedAddress = $address_explode[0];
          $selectedAddressId = $address_explode[1];

          $selectedDate = $_POST["selectedDate"];
          $orderDate = date("Y-m-d H:i:s");

          //Test only
          echo "<br>";
          echo $orderDate;
          echo "<br>";
          echo $selectedPayment;
          echo "<br>";
          echo $selectedAddress;
          echo "<br>";
          echo $selectedDate;
          echo "<br>";

          //Place order
          $sql = "INSERT INTO customer_order (FK_customer_id, order_date, order_total, FK_status_id, order_address, order_payment, delivery_date) VALUES ('$customerId', '$orderDate', '$totalPrice', '1', '$selectedAddress', '$selectedPayment', '$selectedDate')";
          $results = mysqli_query($conn, $sql);
          if ($results) {
            echo "Order Has Been Placed!";
            $thisOrderId = mysqli_insert_id($conn);
          }
          else {
            echo mysqli_error($conn);
          }
          echo "<br>";

          foreach ($itemsInCart as $key => $value) {
            //Key is product_id, value is quantity
            //Update order item
            $sql = "INSERT INTO order_item (FK_order_id, FK_product_id, quantity) VALUES ('$thisOrderId', '$key', '$value')";
            $results = mysqli_query($conn, $sql);
            //Test only
            if ($results){
              echo "(Test only) Update Order Item";
            }
            echo "<br>";

            //Update stock
            $sql = "UPDATE product SET stock = stock - $value WHERE product_id = '$key'";
            $results = mysqli_query($conn, $sql);
            //Test only
            if ($results){
              echo "(Test only) Update stock";
            }
            echo "<br>";
          }
          //Clear the user's shopping cart
          $sql = "DELETE FROM item_in_cart WHERE FK_customer_id='$customerId'";
          $results = mysqli_query($conn, $sql);
          if ($results){
            echo "(Test only) Delete item in cart";
          }
          echo "<br>";
          //Update user's default payment_id and address_id
          $sql = "UPDATE user SET FK_payment_id = '$selectedPaymentId', FK_address_id = '$selectedAddressId' WHERE user_id='$customerId'";
          $results = mysqli_query($conn, $sql);
          if ($results){
            echo "(Test only) Update default id";
          }
          echo "<br>";

          mysqli_close($conn); //close connection
        }
        else {
          echo "Nothing selected";
        }
      }

    ?>
  </body>
</html>
