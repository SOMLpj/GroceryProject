<?php
  //start session
  session_start();
  //Get customer id
  $_SESSION['user_id'] = 1;
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
    <link rel="stylesheet" href="checkout.css">
  </head>
  <body>
    <h2>Checkout</h2>

    <?php
      //Get default payment method and shipping address
      $sql = "SELECT FK_payment_id, FK_address_id FROM user WHERE user_id='$customerId'";
      $results = mysqli_query($conn, $sql);
      if ($row=mysqli_fetch_assoc($results)) {
        //Get id first
        $defaultPayment_id = $row['FK_payment_id'];
        $defaultAddress_id = $row['FK_address_id'];

        $sql = "SELECT name_on_card, card_number, exp_month, exp_year, cvc_code FROM customer_payment WHERE payment_id='$defaultPayment_id'";

        $results = mysqli_query($conn, $sql);
        if ($row=mysqli_fetch_assoc($results)){
          $name_on_card = $row['name_on_card'];
          $card_number = $row['card_number'];
          $exp_month = $row['exp_month'];
          $exp_year = $row['exp_year'];
          $cvc_code = $row['cvc_code'];
          //Create the default payment option
          $defaultPaymentOption = "Name: ". $name_on_card. ", Card Number: ". $card_number. ", Expiration Month/Year: ". $exp_month. "/". $exp_year. ", CVC Code: ". $cvc_code;
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
       Payment Method:
       <br>
      <!--Select payment-->
      <select name="payment_option" class="selection">
        <!--First display the default option-->
        <option value="<?php echo $defaultPaymentOption;?>|<?php echo $defaultPayment_id;?>"><?php echo $defaultPaymentOption;?></option>
      <?php
        $sql = "SELECT payment_id, name_on_card, card_number, exp_month, exp_year, cvc_code FROM customer_payment WHERE FK_customer_id='$customerId'";
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
            $cvc_code = $row['cvc_code'];
            //Create payment option
            $paymentOption = "Name: ". $name_on_card. ", Card Number: ". $card_number. ", Expiration Month/Year: ". $exp_month. "/". $exp_year. ", CVC Code: ". $cvc_code;
            ?>
            <option value="<?php echo $paymentOption;?>|<?php echo $payment_id;?>"><?php echo $paymentOption;?></option>

            <?php
          }
        }
           ?>
      </select>
      <a href="newPayment.php">Add New Payment Method</a>
      <br>
      Shipping Address:
      <br>
      <!--Select address-->
      <select name="address_option">
        <!--First display the default option-->
        <option value="<?php echo $defaultAddressOption;?>|<?php echo $defaultAddress_id;?>"><?php echo $defaultAddressOption;?></option>
        <?php
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
              <?php
            }
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
      <input type="submit" value="Select" class="button">
    </form>
    <br>

    <?php
      //Display what customer selected
      echo "Selected Payment Method: ";
      $selectedPayment= $_POST['payment_option'];
      $explode = explode('|', $selectedPayment);
      $selectedPayment = $explode[0];
      $selectedPaymentId = $explode[1];
      echo $selectedPayment;
      echo "<br><br>";

      echo "Selected Shipping Address: ";
      $selectedAddress = $_POST['address_option'];
      $explode = explode('|', $selectedAddress);
      $selectedAddress = $explode[0];
      $selectedAddressId = $explode[1];
      echo $selectedAddress;
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
          <input type="hidden"  name="selectedPayment" value="<?php echo $selectedPayment;?>|<?php echo $selectedPaymentId;?>">
          <input type="hidden"  name="selectedAddress" value="<?php echo $selectedAddress;?>|<?php echo $selectedAddressId;?>">
          <input type="hidden"  name="selectedDate" value="<?php echo $selectedDate;?>">
          <input type="submit" value="Place Order">
        </form>
        <?php
      }
     ?>
  </body>
</html>
