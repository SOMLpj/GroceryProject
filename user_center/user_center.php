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
        <title>User Center</title>
        <style>
          <?php include "user_center.css";?>
        </style>
        <script>
          <?php include "user_center.js";?>
        </script>
    </head>
    <?php include_once '../component/head_nav/head_nav.php'; ?>
    <body>
      <h1 class="h1">User Center</h2>
      
      <div class="head_button">
        <button class="button" onclick="getHistory()">Order History</button>
        <button class="button" onclick="setAddress()">
          <div class="tooltip">Set Default Address
            <span class="tooltiptext"> You can set your default address</span>
          </div>
        </button>
        <button class="button" onclick="setPayment()">
          <div class="tooltip">Set Default Payment
            <span class="tooltiptext"> You can set your default payment</span>
          </div>
        </button>
      </div>
      <div class="flex-container">
         
        <?php
            $sql = "SELECT first_name, last_name, phone, email, FK_payment_id, FK_address_id FROM user WHERE user_id=$customerId";
            $results = mysqli_query($conn, $sql);
            if ($row=mysqli_fetch_assoc($results)){
              $firstName = $row['first_name'];
              $lastName = $row['last_name'];
              $phone = $row['phone'];
              $email = $row['email'];
            }
            else {
              echo mysqli_error($conn);
            }
            ?>
            <div class="info"> 
                <h3>Basic Information</h3>
                <p><b>Name: </b>
                <?php echo $firstName . " " . $lastName;?></p>
                <p><b>Email: </b>
                <?php echo $email;?></p>
                <p><b>Phone: </b>
                <?php echo $phone;?> </p>
            </div>
        <?php 
            $sql = "SELECT payment_id, name_on_card, card_number, exp_month, exp_year FROM customer_payment WHERE FK_customer_id='$customerId'";
            $results = mysqli_query($conn, $sql);
            ?>
            <div class="payment">
              <h3>Payment Method</h3>
            <?php 
            while ($row=mysqli_fetch_assoc($results))
            {
              $payment_id = $row['payment_id'];
              $name_on_card = $row['name_on_card'];
              $card_number = $row['card_number'];
              $exp_month = $row['exp_month'];
              $exp_year = $row['exp_year'];
              //Create payment option
              $payment= "Ending in " . substr($card_number, 15) . ", Name On Card: " . $name_on_card . ", expires: " . $exp_month . "/" . $exp_year;
              ?>
              <p><?php echo $payment;?></p>
              <?php    
            }
            echo "<p class=\"aInP\"><a href=\"add_payment.php\">Add New Payment</a></p>";
            echo "</div>";

            $sql = "SELECT address_id, street, city, state, zip_code FROM customer_address WHERE FK_customer_id='$customerId'";
            $results = mysqli_query($conn, $sql);
            
            
            ?>
            <div class="address">
              <h3>Shipping Address
              </h3>
            <?php
            while ($row=mysqli_fetch_assoc($results)) {
              $address_id = $row['address_id'];
              $street = $row['street'];
              $city = $row['city'];
              $state = $row['state'];
              $zipCode = $row['zip_code'];
              $address = $street. ", ". $city. ", ". $state. ", ". $zipCode;
              ?>
              <p><?php echo $address;?></p>
              <?php
            }
            echo "<p class=\"aInP\"><a href=\"add_address.php\">Add New Shipping Address</a></p>";
            echo "</div>";
            mysqli_close($conn);
        ?>
      </div>
    </body>
</html>