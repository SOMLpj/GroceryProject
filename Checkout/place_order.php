<?php
  //Start session
  session_start();
  //Get customer id
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
    <style>
      <?php include "checkout.css"; ?>
    </style>
    <script>
      <?php include "checkout.js"; ?>
    </script>
  </head>
  <body>
    <?php
      $total = 0;
      $totalBeforeTax = 0;
      $totalWeight = 0;
      $freeDeliveryWeight = 20; //In pounds
      $deliveryFee = 5;
      $shippingFee = 0;
      //Get every item id and quantity in the cart
      $sql = "SELECT FK_product_id, quantity FROM item_in_cart WHERE FK_customer_id='$customerId'";
      $results = mysqli_query($conn, $sql);

      if (mysqli_num_rows($results) === 0) {
        echo "<h3>Sorry, there is no item in your shopping cart. Please go back to shopping.</h3><br>";
        echo "<button class=\"returnButton\" onclick=\"continueShopping()\">Continue Shopping</button>";
        exit("");
      }
      
      $itemsInCart = array(); //Array for save item id and quantity
      $notEnoughStockItems = array(); //Array for save not enough stock item
      
      while ($row=mysqli_fetch_assoc($results)) {
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

          $totalBeforeTax += $price * $quantity;
          $totalWeight += $shippingWeight * $quantity;
        }
      }

      //If there is item that does not have enough stock, print it out and stop execute rest of the code so user cannot place the order.
      if (!empty($notEnoughStockItems)){

        echo "Sorry, for ";
        foreach ($notEnoughStockItems as $value) {
           echo $value . ", ";
        }
        echo " we do not have enough stock for your order. Please <a href=\"checkout.php\">Go Back To Shopping Cart</a>.";

        exit(""); 
      }

      $orderTax = $totalBeforeTax * $salesTax;
      $orderTax = round($orderTax, 2);//Round to 2 decimal
      $total = $totalBeforeTax + $orderTax;
      //Add delivery fee if total weigtht is over max free delivery weight
      if ($totalWeight >= $freeDeliveryWeight){
        $shippingFee = $deliveryFee;
        $total += $shippingFee;
      }
      $total = round("$total", 2); //Round to 2 decimal

      if (isset($_POST["selectedPayment"]) && isset($_POST["selectedAddress"]) && isset($_POST["selectedDate"])){
        if ($_POST["selectedPayment"] && $_POST["selectedAddress"] && $_POST["selectedDate"]){
          $selected_payment = $_POST["selectedPayment"];
          $payment_explode = explode('|', $selected_payment);
          $selectedPayment = $payment_explode[0];
          $selectedPaymentId = $payment_explode[1];

          $selected_address = $_POST["selectedAddress"];
          $address_explode = explode('|', $selected_address);
          $selectedAddress = $address_explode[0];
          $selectedAddressId = $address_explode[1];

          $selectedDate = $_POST["selectedDate"];
          //The time order been placed
          $orderDate = date("Y-m-d H:i:s");

          //Place order
          $sql = "INSERT INTO customer_order (FK_customer_id, order_date, order_total, FK_status_id, order_address, order_payment, delivery_date) VALUES ('$customerId', '$orderDate', '$total', '1', '$selectedAddress', '$selectedPayment', '$selectedDate')";
          $results = mysqli_query($conn, $sql);
          if ($results) {
            echo "<h2>Thank You! Your Order Has Been Placed!</h2>";
            //Get this order's id
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
    <button class="returnButton" onclick="continueShopping()">Continue Shopping</button>
  </body>
</html>
