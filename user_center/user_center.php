<?php
    session_start();
?>

<html>
<link rel="stylesheet" href="styles.css">
</html>

<!-- SESSION STUFF LATER -->
<?php
?>

<body style = "background-color: rgba(248, 245, 242, 0.1);">
    <p style = "text-align:center; font-size: 40; padding-top: 100"><b>User Center</b></p>
<div style = "padding-top: 50; display: flex; justify-content: center;">
<?php     
    $conn = mysqli_connect("sql3.freesqldatabase.com", "sql3402886", "gn4yJmWUfg","sql3402886");
    if (!$conn) {  
        die("Connection failed: " . mysqli_connect_error());
    }
    $user_id = 1;
    echo $user_id;
    


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

    
    echo "<div class=\"input\">";
    echo"<p class=\"font\">";
    echo "<b>" . $first_name;
    echo " " . $last_name;
    echo "<br>";
    echo "<br>";    
    echo $email;
    echo "<br>";
    echo "<br>";
    echo $phone;
    echo "<br>";
    echo "<br>";
    echo $payment;
    echo "<br>";
    echo "<br>";
    echo $num;
    echo "<br>";
    echo "<br>";
    echo $month;
    echo "<br>";
    echo "<br>";
    echo $year;
    echo "<br>";
    echo "<br>";
    echo $address . "</b>";
    echo "</p>";
    echo "</div>";
?>   
</div>
</body>