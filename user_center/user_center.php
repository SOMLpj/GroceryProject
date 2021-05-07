<?php 
  session_start();
  //Get customer id
  $_SESSION['user_id'] = 1;
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
    <body>
      <h1 class="h1">User Center</h2>
      <div class="head_button">
        <button class="history_button" onclick="getHistory()">Order History</button>
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
                <b>Name: </b>
                <?php echo $firstName . " " . $lastName;?>&nbsp&nbsp
                
                <b>Email: </b>
                <?php echo $email;?>&nbsp&nbsp
                <b>Phone: </b>
                <?php echo $phone;?>
              
            </div>
        <?php 
            $sql = "SELECT payment_id, name_on_card, card_number, exp_month, exp_year, cvc_code FROM customer_payment WHERE FK_customer_id='$customerId'";
            $results = mysqli_query($conn, $sql);
            ?>
            <div class="payment">
              <h3>Payment Method
              <div class="tooltip">*
                  <span class="tooltiptext"> By clicking the Set As Default button, your default payment will be set. It will be the first option when you placing order next time!</span>
                </div>
              </h3>
            <?php 
            while ($row=mysqli_fetch_assoc($results))
            {
              $payment_id = $row['payment_id'];
              //Create other options but not include the default
              $name_on_card = $row['name_on_card'];
              $card_number = $row['card_number'];
              $exp_month = $row['exp_month'];
              $exp_year = $row['exp_year'];
              $cvc_code = $row['cvc_code'];
              //Create payment option
              $payment= "Ending in " . substr($card_number, 15) . ", Name On Card: " . $name_on_card . ", expires: " . $exp_month . "/" . $exp_year;
              ?>
             
              <form name="setDefaultPayment" method="post">
                <p><?php echo $payment;?>
                <input type="hidden" name="setPayment" value="<?php echo $payment_id;?>">
                <input type="submit" value="Set As Default" class="button"></p>
              </form>
              <?php    
            }
            echo "</div>";
            if (isset($_POST['setPayment'])) {
              if ($_POST['setPayment'])
              {
                $default_payment = $_POST['setPayment'];
                $sql = "UPDATE user SET FK_payment_id = '$default_payment' WHERE user_id='$customerId'";
                $results = mysqli_query($conn, $sql);
              }

              $sql = "SELECT address_id, street, city, state, zip_code FROM customer_address WHERE FK_customer_id='$customerId'";
              $results = mysqli_query($conn, $sql);
            }
            
            ?>
            <div class="address">
              <h3>Shipping Address
              <div class="tooltip">*
                  <span class="tooltiptext"> By clicking the Set As Default button, your default address will be set. It will be the first option when you placing order next time!</span>
                </div>
              </h3>
            <?php
            while ($row=mysqli_fetch_assoc($results)) {
              $address_id = $row['address_id'];
              $street = $row['street'];
              $city = $row['city'];
              $state = $row['state'];
              $zip_code = $row['zip_code'];
              $address = $street. ", ". $city. ", ". $state. ", ". $zipCode;
              ?>
              <form method="post">
                <p><?php echo $address;?>
                <input type="hidden" name="setAddress" value="<?php echo $address_id;?>">
                <input type="submit" value="Set As Default" class="button"></p>
              </form>
            
              <?php
            }
            echo "</div>";
            if (isset($_POST['setAddress'])) {
              if ($_POST['setAddress']) {
                $default_address = $_POST['setAddress'];
                $sql = "UPDATE user SET FK_address_id = '$default_address' WHERE user_id='$customerId'";
                $results = mysqli_query($conn, $sql);
              }
            }
            
        ?>
      </div>
    </body>
</html>