<?php
    session_start();
?>

<html>
<link rel="stylesheet" href="style.css">
</html>

<!-- SESSION STUFF LATER -->
<?php
?>

<body class = "back">
    <p style = "text-align:center; font-size: 40; padding-top: 100; font-family: Verdana, Geneva, Tahoma, sans-serif;"><b>User Center</b></p>
<div style = "padding-top: 50; display: flex; justify-content: center;">
<?php     
    $conn = mysqli_connect("sql3.freesqldatabase.com", "sql3402886", "gn4yJmWUfg","sql3402886");
    if (!$conn) {  
        die("Connection failed: " . mysqli_connect_error());
    }
    // $user_id = $_SESSION['user_id']; COMMENTED OUT TO SHOWCASE USER CENTER. 
    $user_id = 1;

    $sql = "SELECT first_name FROM user WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $first_name = mysqli_fetch_assoc($result)['first_name'];

    $sql = "SELECT last_name FROM user WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $last_name =  mysqli_fetch_assoc($result)['last_name'];

    $sql = "SELECT phone FROM user WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $phone =  mysqli_fetch_assoc($result)['phone'];

    $sql = "SELECT email FROM user WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $email =  mysqli_fetch_assoc($result)['email'];

    $sql = "SELECT order_address FROM customer_order WHERE FK_customer_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $address =  mysqli_fetch_assoc($result)['order_address'];

    $sql = "SELECT name_on_card FROM customer_payment WHERE FK_customer_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $payment =  mysqli_fetch_assoc($result)['name_on_card'];

    $sql = "SELECT card_number FROM customer_payment WHERE FK_customer_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $num =  mysqli_fetch_assoc($result)['card_number'];

    $sql = "SELECT exp_month FROM customer_payment WHERE FK_customer_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $month =  mysqli_fetch_assoc($result)['exp_month'];

    $sql = "SELECT exp_year FROM customer_payment WHERE FK_customer_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $year =  mysqli_fetch_assoc($result)['exp_year'];

    $sql = "SELECT FK_payment_id, FK_address_id FROM user WHERE user_id='$user_id'";
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
        $defaultPaymentOption = substr($card_number, 15);
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
    

    
    echo "<div class=\"info\">";
        echo"<p class=\"font\">";
            echo "<b>" . "Basic Information". "</b>";
            echo "<br>";
            echo "<br>";
            echo "<b>" . "Name: ". "</b>" . $first_name;
            echo " " . $last_name;
            echo "<br>";
            echo "<br>";    
            echo "<b>" . "Email: ". "</b>" . $email ;
            echo "<br>";
            echo "<br>";
            echo "<b>" . "Phone Number: ". "</b>" . $phone;
            echo "<br>";
            echo "<br>";
        echo "</p>";
    echo "</div>";
    
    echo "<div class=\"payment\">";
        echo"<p class=\"font\">";
            echo "<b>" . "Payment Information". "</b>";
            echo "<br>";
            echo "<br>";
            echo "<b>" . "Name on Card: ". "</b>" . $payment;
            echo "<br>";
            echo "<br>";
            echo "<b>" . "Card ends with: ". "</b>" . $defaultPaymentOption;
            echo "<br>";
            echo "<br>";
            echo "<b>" . "Expiration Date    ". "</b>" . $month . "/";
            echo $year;
        echo "</p>";
    echo "</div>";
    
    echo "<div class=\"shipping\">";
        echo"<p class=\"font\">";
        echo "<b>" . "Shipping Address". "</b>";
        echo "<br>";
        echo "<br>";
        echo $defaultAddressOption;      
        echo "</p>";
    echo "</div>";
?>   
</div>
<div style = "text-align:center; padding-top: 200">
    <h3><a href="..main_page\catagories.php">Main Page</h3></a>
</div>    
</body>
