<?php
  //Sales Tax rate in San Jose: 9.25%
  //start session
  session_start();
  //Set default timezone
  date_default_timezone_set("America/Los_Angeles");
  //Get current time
  $currentTime = date("H:i:s");
  //The latest time eligible for same day delivery
  $sameDayDeliveryTime = date_create_from_format("H:i:s", "16:00:00");
  $sameDayDeliveryTime = date_format($sameDayDeliveryTime, "H:i:s");
  //Create 3 options for customer select delivery date
  $firstDay = date("Y-m-d");
  $secondDay = date("Y-m-d", strtotime("+1 days"));
  $thirdDay = date("Y-m-d", strtotime("+2 days"));
  //If current time is later than eligible time, make new 3 days start from
  //tomorrow
  if ($currentTime > $sameDayDeliveryTime){
    $firstDay = date("Y-m-d", strtotime("+1 days"));
    $secondDay = date("Y-m-d", strtotime("+2 days"));
    $thirdDay = date("Y-m-d", strtotime("+3 days"));
  }
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
  </head>
  <body>
    <h2>Checkout</h2>
    Payment Method:
    <br>
    <form action="" method="post">
      <!--Select payment-->
      <select name="payment_option">
      <?php
        $sql = "SELECT payment_id, name_on_card, card_number, exp_month, exp_year, cvc_code FROM customer_payment WHERE FK_customer_id='1'";
        $results = mysqli_query($conn, $sql);

        while ($row=mysqli_fetch_assoc($results))
        {
          $payment_id = $row['payment_id'];
          $name_on_card = $row['name_on_card'];
          $card_number = $row['card_number'];
          $exp_month = $row['exp_month'];
          $exp_year = $row['exp_year'];
          $cvc_code = $row['cvc_code'];
          //Create payment option
          $paymentOption = "Name: ". $name_on_card. ", Card Number: ". $card_number. ", Expiration Month/Year: ". $exp_month. "/". $exp_year. ", CVC Code: ". $cvc_code;
          ?>
          <option value="<?php echo $paymentOption;?>"><?php echo $paymentOption;?></option>

          <?php
        }
           ?>
      </select>
      <a href="newPayment.php">Add New Payment Method</a>
      <br>
      Shipping Address:
      <br>
      <!--Select address-->
      <select name="address_option">
        <?php
          $sql1 = "SELECT address_id, street, city, state, zip_code FROM customer_address WHERE FK_customer_id='1'";
          $results1 = mysqli_query($conn, $sql1);

          while ($row=mysqli_fetch_assoc($results1))
          {
            $address_id = $row['address_id'];
            $street = $row['street'];
            $city = $row['city'];
            $state = $row['state'];
            $zipCode = $row['zip_code'];

            $addressOption = "Street: ". $street. ", City: ". $city. ", State: ". $state. ", Zip Code: ". $zipCode;
            ?>
            <option value="<?php echo $addressOption;?>"><?php echo $addressOption;?></option>

            <?php
          }
             ?>
      </select>
      <a href="newAddress.php">Add New Shipping Address</a>
      <br>
      Delivery Date:
      <br>
      <!--Select delivery date-->
      * Note: Same day delivery only apply for order before 4:00 pm <br>
      <select name="date_option">
        <option><?php echo $firstDay;?></option>
        <option><?php echo $secondDay;?></option>
        <option><?php echo $thirdDay;?></option>
      </select>
      <br>
      <input type="submit" value="Select">
    </form>
    <br>

    <?php
      //Display what customer selected
      echo "Selected Payment Method: ";
      $selectedAddress= $_POST['address_option'];
      echo $selectedAddress;
      echo "<br><br>";

      echo "Selected Shipping Address: ";
      $selectedPayment = $_POST['payment_option'];
      echo $selectedPayment;
      echo "<br><br>";

      echo "Selected Delivery Date: ";
      $selectedDate = $_POST['date_option'];
      echo $selectedDate;
      echo "<br><br>";

      //If everything is selected, display the place order button. Else notify
      //customer to select
      if (is_null($selectedPayment) || is_null($selectedAddress) || is_null($selectedDate))
      {
        echo "* At Least One Delivery Information Was Not Selected.";
      }
      else {
        ?>
        <form action="/placeOrder.php" method="post">
          <input type="hidden"  name="paymentS" value="<?php echo $selectedPayment;?>">
          <input type="hidden"  name="addressS" value="<?php echo $selectedAddress;?>">
          <input type="hidden"  name="dateS" value="<?php echo $selectedDate;?>">
          <input type="submit" value="Place Order">
        </form>
        <?php
      }
     ?>
  </body>
</html>
