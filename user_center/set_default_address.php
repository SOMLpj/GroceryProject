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
    <title>Set Default Address</title>
    <style><?php include "set_default.css" ?></style>
    <script><?php include "set_default.js" ?></script>
  </head>
  <?php include_once '../component/head_nav/head_nav.php'; ?>
  <body>
    <br><br>
    <h1>Set Default Address</h1>
    <div class="setDefault">
  <form action="" method="post" name="set_default_address" class="setDefault_from">
  <?php 
    $sql = "SELECT address_id, street, city, state, zip_code FROM customer_address WHERE FK_customer_id='$customerId'";
    $results = mysqli_query($conn, $sql);
            
    while ($row=mysqli_fetch_assoc($results))
    {
      $address_id = $row['address_id'];
      $street = $row['street'];
      $city = $row['city'];
      $state = $row['state'];
      $zipCode = $row['zip_code'];
      $address = $street. ", ". $city. ", ". $state. ", ". $zipCode;
      ?>
      <input type="radio" name="set_address" value="<?php echo $address_id;?>" class="input">
      <label for="set_address"><?php echo $address;?></label>
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
    if(isset($_POST['set_address'])) {
      if ($_POST['set_address'])
      {
        $default_address = $_POST['set_address'];
        $sql = "UPDATE user SET FK_address_id = '$default_address' WHERE user_id='$customerId'";
        $results = mysqli_query($conn, $sql);
        if ($results) {
          echo "<script>setAddressSuccess()</script>";
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