<html>
  <header>
    <title>Login Page</title>
  </header>
  <body>
    <h1><k>Login</k></h1>
    <form action="login.php" method="post">
      <l></l>
      <input type="text" name="customer_id">
      <input type="text" name="password_encrypted">
      <input type="submit">
    </form>
    <table>
      <thead>
        <tr>
        <th>&emsp;&ensp;</th>
        <th><h2><l>username</l></h2></th>
        <th>&emsp;&emsp;&emsp;&emsp;</th>
        <th><h2>password</h2></th>
        </tr>
      </thead>
  </table>
  </body>
</html>
</body>





<html>
  <head>
    <title> Login Page</title>
    <link rel = "stylesheet" href = "style.css">
  </head>
  <body>
    <?php
    $logged_in = false;
    if(isset($_POST["customer_id"]) && isset($_POST["password_encrypted"])) {
      if($_POST["customer_id"] && $_POST["password_encrypted"]) {
          $conn = mysqli_connect("sql3.freesqldatabase.com", "sql3402886", "gn4yJmWUfg", "sql3402886");
          $customer_id = $_POST['customer_id'];
          $password_encrypted = $_POST['password_encrypted'];
          if(!$conn) {
            die("Connection failed:" .mysqli_connect_error());
          }

          $sql = "SELECT password_encrypted FROM customer WHERE customer_id  = '$customer_id'";
          $results = mysqli_query($conn, $sql);

          if($results) {
            $row = mysqli_fetch_assoc($results);
            $hash = $row["password_encrypted"];
            if(password_verify($password_encrypted, $hash)) {
              $logged_in = true;
              $sql = "SELECT * FROM customer";
              $results = mysqli_query($conn, $sql);
              echo "login succuessful";
            } else {
                echo "password incorrect";
              }
          } else {
             echo mysqli_error($conn);
             }
             mysqli_close($conn);
      } else {
         echo "Nothing was submitted.";
        }
    }
    ?>
  </body>
</html>
