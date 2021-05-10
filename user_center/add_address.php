<?php
  if(!isset($_SESSION)) {
    //start session
    session_start();
  }
  if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false) {
    echo "<script>notLoginIn()</script>";
  }
  $customerId = $_SESSION['user_id'];
 ?>
<html>
  <head>
    <title>New Shipping Address</title>
    <style>
      <?php include "user_center.css" ?>
    </style>
  </head>
  <?php include_once '../component/head_nav/head_nav.php'; ?>
  <body>
    <div class="addInfo">
    <h2>Add New Shipping Address</h2>
    <form method="post" class="addInfo_form">
      <label for="street">Street:</label>
      <input type="text" name="street" class="input" required><br>
      <label for="city">City:</label>
      <input type="text" name="city" class="input" required><br>
      <label for="state">State:</label>
      <input type="text" name="state" class="input" pattern="[A-Z]{2}" placeholder="Two letters, e.g., CA"><br>
      <label for="zipCode">Zip Code:</label>
      <input type="text" name="zipCode" class="input" pattern="[0-9]{5}" placeholder="Five digit: e.g., 12345"><br>
      <input type="submit" class="button" value="Add">
    </form>
    <?php
      if (isset($_POST["street"]) && isset($_POST["city"]) && isset($_POST["state"]) && isset($_POST["zipCode"]))
      {
        if ($_POST["street"] && $_POST["city"] && $_POST["state"] && $_POST["zipCode"])
        {
          //create connection
          $conn = mysqli_connect("sql3.freesqldatabase.com", "sql3402886", "gn4yJmWUfg", "sql3402886");
          //check connection
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }

          $street = $_POST["street"];
          $city = $_POST["city"];
          $state = $_POST["state"];
          $zipCode = $_POST["zipCode"];

          //add shipping address
          $sql = "INSERT INTO customer_address (FK_customer_id, street, city, state, zip_code) VALUES ('$customerId', '$street', '$city', '$state', '$zipCode')";
          $results = mysqli_query($conn, $sql);
          if ($results) {
            echo "New Shipping Address Added!";
          }
          else {
            echo mysqli_error($conn);
          }

          mysqli_close($conn); //close connection
        }
        else {
          echo "Nothing was added";
        }
      }
      mysqli_close($conn);
    ?>
    <br>
    <a href="user_center.php">Go Back To User Center</a>
    </div>
  </body>
</html>
