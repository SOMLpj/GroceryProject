<html>
  <head>
    <title>New Payment Method</title>
    <style>
      <?php include "checkout.css" ?>
    </style>
  </head>
  <?php include_once '../component/head_nav/head_nav.php'; ?>
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
  <body>
    <div class="addInfo">
    <h2>Add New Payment Method</h2>
    <form method="post" class="addInfo_form">
      <label for="nameOnCard">Name On Card:</label>
      <input type="text" name="nameOnCard" class="input" required><br>
      <label for="cardNumber">Card Number:</label>
      <input type="text" name="cardNumber" class="input" pattern="[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}" placeholder="Number only: #### #### #### ####" style="width: 200;" required><br>
      <label for="expirationMonth">Expiration Month:</label>
      <input type="text" name="expirationMonth" class="input" pattern="[0-9]{2}" placeholder="Two digit: e.g., 01" required><br>
      <label for="expirationYear">Expiration Year:</label>
      <input type="text" name="expirationYear" class="input" pattern="[0-9]{4}" placeholder="Four digit: e.g., 1234" required><br>
      <label for="securityCode">Security Code:</label>
      <input type="text" name="securityCode" class="input" pattern="[0-9]{3}" placeholder="Three digit: e.g., 123" required><br>
      <input type="submit" class="button" value="Add">
    </form>
    
    <?php
    if (isset($_POST["nameOnCard"]) && isset($_POST["cardNumber"]) && isset($_POST["expirationMonth"]) && isset($_POST["expirationYear"]) && isset($_POST["securityCode"]))
    {
      if ($_POST["nameOnCard"] && $_POST["cardNumber"] && $_POST["expirationMonth"] && $_POST["expirationYear"] && $_POST["securityCode"])
      {
        //create connection
        $conn = mysqli_connect("sql3.freesqldatabase.com:3306", "sql3402886", "gn4yJmWUfg", "sql3402886");
        //check connection
        if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
        }

        $nameOnCard = $_POST["nameOnCard"];
        $cardNumber = $_POST["cardNumber"];
        $expirationMonth = $_POST["expirationMonth"];
        $expirationYear = $_POST["expirationYear"];
        $securityCode = $_POST["securityCode"];


        //add payment method
        $sql = "INSERT INTO customer_payment (FK_customer_id, name_on_card, card_number, exp_month, exp_year, cvc_code) VALUES ('$customerId', '$nameOnCard', '$cardNumber', '$expirationMonth', '$expirationYear', '$securityCode')";
        $results = mysqli_query($conn, $sql);
        if ($results) {
          echo "New Payment Method Added!";
        }
        else {
          echo mysqli_error($conn);
        }
        mysqli_close($conn); //close connection
      }
      else
      {
          echo "Nothing was Added";
      }
    }

    ?>
      <br>
      <a href="checkout.php">Go Back To Checkout</a>
    </div>
  </body>
</html>
