<html>
  <head>
    <title>New Payment Method</title>
  </head>
  <body>
    <h2>Add New Payment Method</h2>
    <form action="/newPayment.php" method="post">
      <label for="nameOnCard">Name On Card:</label>
      <input type="text" name="nameOnCard"><br>
      <label for="cardNumber">Card Number:</label>
      <input type="text" name="cardNumber" pattern="[0-9].{1,}" placeholder="Number only"><br>
      <label for="expirationMonth">Expiration Month:</label>
      <input type="text" name="expirationMonth" pattern="[0-9]{2}" placeholder="Two digit: e.g., 01"><br>
      <label for="expirationYear">Expiration Year:</label>
      <input type="text" name="expirationYear"pattern="[0-9]{4}" placeholder="Four digit: e.g., 1234"><br>
      <label for="securityCode">Security Code:</label>
      <input type="text" name="securityCode" pattern="[0-9]{3}" placeholder="Three digit: e.g., 123"><br>
      <input type="submit" value="Add">
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
        $sql = "INSERT INTO customer_payment (FK_customer_id, name_on_card, card_number, exp_month, exp_year, cvc_code) VALUES ('1', '$nameOnCard', '$cardNumber', '$expirationMonth', '$expirationYear', '$securityCode')";
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
  </body>
</html>
