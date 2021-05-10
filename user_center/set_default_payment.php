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
    <title>Set Default Payment</title>
    <style><?php include "set_default.css" ?></style>
    <script><?php include "set_default.js" ?></script>
  </head>
  <?php include_once '../component/head_nav/head_nav.php'; ?>
  <body>
    <br><br>
    <h1>Set Default Payment</h1>
    <div class="setDefault">
  <form action="" method="post" name="set_default_payment" class="setDefault_from">
  <?php 
    $sql = "SELECT payment_id, name_on_card, card_number, exp_month, exp_year FROM customer_payment WHERE FK_customer_id='$customerId'";
    $results = mysqli_query($conn, $sql);
            
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
      <input type="radio" name="setPayment" value="<?php echo $payment_id;?>" class="input">
      <label for="setPayment"><?php echo $payment;?></label>
      <br>
      <?php
    }
  ?>
  <input type="submit" value="Set As Default" class="button">
  </form>
  <br>
  <a href="user_center.php">Go Back To User Center</a>
  </div>
  <?php 
    if(isset($_POST['setPayment'])) {
      if ($_POST['setPayment'])
      {
        $default_payment = $_POST['setPayment'];
        $sql = "UPDATE user SET FK_payment_id = '$default_payment' WHERE user_id='$customerId'";
        $results = mysqli_query($conn, $sql);
        if ($results) {
          echo "<script>setPaymentSuccess()</script>";
        }
      }
      else {
        echo "Nothing Set";
      }
    }
    mysqli_close($conn);
  ?>
  </body>
</html>