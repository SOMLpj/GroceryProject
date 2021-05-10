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
  //Set default timezone. (Since our website is only for San Jose downtown area,
  //we only need timezone of San Jose, which is same as Los Angeles)
  date_default_timezone_set("America/Los_Angeles");
  //Get current time
  $currentTime = date("H:i:s");
  //The latest time eligible for same day delivery
  $sameDayDeliveryTime = date_create_from_format("H:i:s", "16:00:00");
  $sameDayDeliveryTime = date_format($sameDayDeliveryTime, "H:i:s");
  //Create 3 options for customer select delivery date
  $firstDay = date("l, M/d");
  $secondDay = date("l, M/d", strtotime("+1 days"));
  $thirdDay = date("l, M/d", strtotime("+2 days"));
  //If current time is later than eligible time, make new 3 days start from
  //tomorrow
  if ($currentTime > $sameDayDeliveryTime){
    $firstDay = date("l, M/d", strtotime("+1 days"));
    $secondDay = date("l, M/d", strtotime("+2 days"));
    $thirdDay = date("l, M/d", strtotime("+3 days"));
  }
  //Sales Tax rate in San Jose, 95112: 9.25%
  $salesTax = 0.0925;
  //create connection
  $conn = mysqli_connect("sql3.freesqldatabase.com:3306", "sql3402886", "gn4yJmWUfg", "sql3402886");
  //check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
 ?>
<html>
  <head>
    <title>Checkout</title>
    <style>
      <?php include "checkout.css" ?>
    </style>
    <script>
      <?php include "checkout.js"; ?>
    </script>
  </head>
  <?php include_once '../component/head_nav/head_nav.php'; ?>
  <body>
    <h1>Checkout</h1>
    <br>
    <?php
      $total = 0;
      $totalBeforeTax = 0;
      $totalDiscount = 0;
      $totalAfterDiscount = 0;
      $totalWeight = 0;
      $freeDeliveryWeight = 20; //In pounds
      $deliveryFee = 5;
      $shippingFee = 0;
      //Get every item id and quantity in the cart
      $sql = "SELECT FK_product_id, quantity FROM item_in_cart WHERE FK_customer_id='$customerId'";
      $results = mysqli_query($conn, $sql);
      //If no item in cart, stop the rest of code so user cannot place order.
      if (mysqli_num_rows($results) === 0) {
        echo "<h3>Sorry, there is no item in your shopping cart. Please go back to shopping.</h3>";
        echo "<button class=\"returnButton\" onclick=\"continueShopping()\">Continue Shopping</button>";
        exit("");
      }
     
      $itemsInCart = array(); //Array for save item id and quantity
      $notEnoughStockItems = array(); //Array for save not enough stock item
      
      while ($row=mysqli_fetch_assoc($results)) {
        $orderProductId = $row['FK_product_id'];
        $quantity = $row['quantity'];
        //Get the item's stock from product database
        $sql1 = "SELECT stock, product_name, price, discount, shipping_weight FROM product WHERE product_id='$orderProductId'";
        $results1 = mysqli_query($conn, $sql1);
        while ($row=mysqli_fetch_assoc($results1)){
          $stock = $row['stock'];
          $productName = $row['product_name'];
          $price = $row['price'];
          $discount = $row['discount'];
          $shippingWeight = $row['shipping_weight'];
        }
        //If the item quantity in the cart is more than the avaliable stock,
        //this order should not be placed
        if ($quantity > $stock){
          array_push($notEnoughStockItems, $productName);
        }
        else {
          $product = array($productName, $price, $quantity);
          array_push($itemsInCart, $product);
          //Make the array key = product_id, value = quantity

          $totalBeforeTax += $price * $quantity;
          $totalDiscount += $price * ($discount / 100) * $quantity;
          $totalWeight += $shippingWeight * $quantity;
        }
      }
      //If there is item that does not have enough stock, print it out and stop execute rest of the code so user cannot place the order.
      if (!empty($notEnoughStockItems)){

        echo "<h3>Sorry, for ";
        foreach ($notEnoughStockItems as $value) {
           echo $value . ", ";
        }
        echo " we do not have enough stock for your order. Please <a href=\"../shopping_cart/src/cart.php\">Go Back To Shopping Cart</a>.</h3>";

        exit("");
      }
      $totalAfterDiscount = $totalBeforeTax - $totalDiscount;
      $orderTax = $totalAfterDiscount * $salesTax;
      $orderTax = round($orderTax, 2);//Round to 2 decimal
      $total = $totalAfterDiscount + $orderTax;
      //Add delivery fee if total weigtht is over max free delivery weight
      if ($totalWeight >= $freeDeliveryWeight){
        $shippingFee = $deliveryFee;
        $total += $shippingFee;
      }
      $total = round("$total", 2); //Round to 2 decimal

      ?>
      <div class="flex-container">
      <!--Review order (Items in cart)-->
        <div class="cart">
          <table width="500px">
            <tr class="tTitle">
              <th></th>
              <th>Review Order</th>
              <th></th>
            </tr>
            <tr>
              <th>Item</th>
              <th>Price</th>
              <th>Quantity</th>
            </tr>
            <tr>
              <?php
              //Only three columns: item, price, quantity.
              for ($row = 0; $row < count($itemsInCart); $row ++) {
                for ($col = 0; $col < 3; $col ++) {
                  //The second column is price, so add $ symbol
                  if ($col === 1)
                  {
                  ?>
                    <td>$<?php echo $itemsInCart[$row][$col];?></td>
                  <?php
                  }
                  else {
                    ?>
                    <td><?php echo $itemsInCart[$row][$col];?></td>
                    <?php
                  }
                }
                ?>
            </tr>
                <?php
              }
              ?>
          </table>
        </div>
        <!--Order summary-->
        <div class="order_summary">
          <table width="325px">
            <tr class="tTitle">
              <th>Order Summary</th>
            </tr>
            <tr>
              <td>Items:</td>
              <td>$<?php echo $totalBeforeTax;?></td>
            </tr>
            <tr class="discount">
              <td>Discount:</td>
              <td>-$<?php echo $totalDiscount;?></td>
            </tr>
            <tr>
              <td>Shipping fee:
                <!--Notify the delivery fee police-->
                <div class="tooltip">*
                  <span class="tooltiptext"> Extra shipping fee for order over 20 pounds</span>
                </div>
              </td>
              <td>$<?php echo $shippingFee;?></td>
            </tr>
            <tr>
              <td>Tatal Before Tax:</td>
              <td>$<?php echo $totalAfterDiscount;?></td>
            </tr>
            <tr>
              <td>Estimated Tax:</td>
              <td>$<?php echo $orderTax;?></td>
            </tr>
            <tr class="total">
              <td>Order Total:</td>
              <td>$<?php echo $total;?></td>
            </tr>
          </table>
        </div>
    </div>
    <?php
      //Get default payment method and shipping address
      $sql = "SELECT FK_payment_id, FK_address_id FROM user WHERE user_id='$customerId'";
      $results = mysqli_query($conn, $sql);
      if ($row=mysqli_fetch_assoc($results)) {
        //Get id first
        $defaultPayment_id = $row['FK_payment_id'];
        $defaultAddress_id = $row['FK_address_id'];

        $sql = "SELECT name_on_card, card_number, exp_month, exp_year FROM customer_payment WHERE payment_id='$defaultPayment_id'";

        $results = mysqli_query($conn, $sql);
        if ($row=mysqli_fetch_assoc($results)){
          $name_on_card = $row['name_on_card'];
          $card_number = $row['card_number'];
          $exp_month = $row['exp_month'];
          $exp_year = $row['exp_year'];
          //Create the default payment option
          $defaultPaymentOption = "Ending in " . substr($card_number, 15) . ", Name On Card: " . $name_on_card . ", expires: " . $exp_month . "/" . $exp_year;
        }

        $sql = "SELECT street, city, state, zip_code FROM customer_address WHERE address_id='$defaultAddress_id'";
        $results = mysqli_query($conn, $sql);
        if ($row=mysqli_fetch_assoc($results)){
          $street = $row['street'];
          $city = $row['city'];
          $state = $row['state'];
          $zipCode = $row['zip_code'];
          //create the default address option
          $defaultAddressOption = $street. ", ". $city. ", ". $state. ", ". $zipCode;
        }
      }
      else {
        echo mysqli_error($conn);
      }
     ?>

       <form action="" method="post">
         <br>
         <h class="h">Payment Method:</h>
         <br>
        <!--Select payment-->
        <select name="payment_option" class="selection">
          <!--First display the default option-->
          <?php 
            if (!empty($defaultPaymentOption)){
              ?>
              <option value="<?php echo $defaultPaymentOption;?>|<?php echo $defaultPayment_id;?>"><?php echo $defaultPaymentOption;?></option>
              <?php 
            }
          $sql = "SELECT payment_id, name_on_card, card_number, exp_month, exp_year FROM customer_payment WHERE FK_customer_id='$customerId'";
          $results = mysqli_query($conn, $sql);

          while ($row=mysqli_fetch_assoc($results))
          {
            $payment_id = $row['payment_id'];
            //Create other options but not include the default
            if ($payment_id !== $defaultPayment_id){
              $name_on_card = $row['name_on_card'];
              $card_number = $row['card_number'];
              $exp_month = $row['exp_month'];
              $exp_year = $row['exp_year'];
              //Create payment option
              $paymentOption = "Ending in " . substr($card_number, 15) . ", Name On Card: " . $name_on_card . ", expires: " . $exp_month . "/" . $exp_year;
              echo $paymentOption;
              ?>
              <option value="<?php echo $paymentOption;?>|<?php echo $payment_id;?>"><?php echo $paymentOption;?></option>
              <!--Make two values. Payment id for later update default value-->
              <?php
            }
          }
             ?>
        </select>
        <a href="newPayment.php">Add New Payment Method</a>
        <br>
        <h class="h">Shipping Address:</h>
        <br>
        <!--Select address-->
        <select name="address_option" class="selection">
          <!--First display the default option-->
          <?php
            if (!empty($defaultAddressOption)){
              ?>
              <option value="<?php echo $defaultAddressOption;?>|<?php echo $defaultAddress_id;?>"><?php echo $defaultAddressOption;?></option>
              <?php
            } 
         
            $sql = "SELECT address_id, street, city, state, zip_code FROM customer_address WHERE FK_customer_id='$customerId'";
            $results = mysqli_query($conn, $sql);

            while ($row=mysqli_fetch_assoc($results))
            {
              $address_id = $row['address_id'];
              if ($address_id !== $defaultAddress_id){
                $street = $row['street'];
                $city = $row['city'];
                $state = $row['state'];
                $zipCode = $row['zip_code'];
                //Create other options but not include the default
                $addressOption = $street. ", ". $city. ", ". $state. ", ". $zipCode;
                ?>
                <option value="<?php echo $addressOption;?>|<?php echo $address_id;?>"><?php echo $addressOption;?></option>
                <!--Make two values. Address id for later update default value-->
                <?php
              }
            }
               ?>
        </select>
        <a href="newAddress.php">Add New Shipping Address</a>
        <br>

        <!--Select delivery date-->
        <h class="h">Delivery Date:</h>
        <!--Notify delivery police-->
        <div class="tooltip">*
          <span class="tooltiptext"> Same day delivery only apply for order before 4:00 pm </span>
        </div>
        <br>
        <select name="date_option" class="selection">
          <option><?php echo $firstDay;?></option>
          <option><?php echo $secondDay;?></option>
          <option><?php echo $thirdDay;?></option>
        </select>
        <br>
        <input type="submit" value="Select" class="button">
      </form>

      <?php
      echo "<br><br>";
      //Display what customer selected
      echo '<h class="h">Selected Payment Method: </h>';
      if (isset($_POST['payment_option'])) {
        if ($_POST['payment_option']) {
          $selected_payment= $_POST['payment_option'];
          //Separate name and id. Display payment name only
          $payment_explode = explode('|', $selected_payment);
          $selectedPayment = $payment_explode[0];
          $selectedPaymentId = $payment_explode[1];
          echo $selectedPayment;
        }
      }
      echo "<br><br>";

      echo '<h class="h">Selected Shipping Address: </h>';
      if (isset($_POST['address_option'])) {
        if ($_POST['address_option']) {
          $selected_address = $_POST['address_option'];
          //Separate name and id. Display address name only
          $address_explode = explode('|', $selected_address);
          $selectedAddress = $address_explode[0];
          $selectedAddressId = $address_explode[1];
          echo $selectedAddress;
        }
      }
      echo "<br><br>";

      echo '<h class="h">Selected Delivery Date: </h>';
      if (isset($_POST['date_option'])) {
        if ($_POST['date_option']) {
          $selectedDate = $_POST['date_option'];
          //Display date
          echo $selectedDate;
        }
      }
      echo "<br><br>";

      //If everything is selected, display the place order button. Else notify customer to select
      if (empty($selectedPayment) || empty($selectedAddress) || empty($selectedDate))
      {
        echo "*At Least One Delivery Information Was Not Selected.";
      }
      else {
        ?>
        <!--Place order with selected options-->
        <form action="place_order.php" method="post">
          <input type="hidden" name="selectedPayment" value="<?php echo $selectedPayment;?>|<?php echo $selectedPaymentId;?>">
          <input type="hidden" name="selectedAddress" value="<?php echo $selectedAddress;?>|<?php echo $selectedAddressId;?>">
          <input type="hidden" name="selectedDate" value="<?php echo $selectedDate;?>">
          <input type="submit" value="Place Order" class="button">
        </form>
        <?php
      }
     ?>
     <br><br>
  </body>
</html>
