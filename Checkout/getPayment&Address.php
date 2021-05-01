<?php
  //Get customer id
  $_SESSION['user_id'] = 1;
  $customerId = $_SESSION['user_id'];
  //create connection
  $conn = mysqli_connect("sql3.freesqldatabase.com:3306", "sql3402886", "gn4yJmWUfg", "sql3402886");
  //check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

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
  echo $defaultPaymentOption;
  echo "<br>";

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
      $paymentOption = "Ending in " . substr($card_number, 15) . ", Name On Card: " . $name_on_card . ", expires: " . $exp_month . "/" . $exp_year;
      echo $paymentOption;
      echo "<br>";
    }
  }
  echo $defaultAddressOption;
  echo "<br>";

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
      echo $addressOption;
      echo "<br>";
    }
  }

  //Just Let you know I get default values first so you can display them at the top. I just print out everything in the db so you can change the format you want to display. You can delete this comment later.
?>
