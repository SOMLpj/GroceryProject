<html>
  <head>
    <title>New Shipping Address</title>
  </head>
  <body>
    <h2>Add New Shipping Address</h2>
    <form action="/newAddress.php" method="post">
      <label for="street">Street:</label>
      <input type="text" name="street" required><br>
      <label for="city">City:</label>
      <input type="text" name="city" required><br>
      <label for="state">State:</label>
      <input type="text" name="state" pattern="[A-Z]{2}" placeholder="Two letters, e.g., CA"><br>
      <label for="zipCode">Zip Code:</label>
      <input type="text" name="zipCode" pattern="[0-9]{5}" placeholder="Five digit: e.g., 12345"><br>
      <input type="submit" value="Add">
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
          $sql = "INSERT INTO customer_address (FK_customer_id, street, city, state, zip_code) VALUES ('1', '$street', '$city', '$state', '$zipCode')";
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
    ?>
    <br>
    <a href="checkout.php">Go Back To Checkout</a>
  </body>
</html>
