<?php
    session_start();
?>

<html>
  <header>
    <title>Login Page</title>
  </header>
  <body>
    <h1><k>Login</k></h1>
    <form action="login.php" method="post">
      <l></l>
      <input type="text" name="email">
      <input type="password" name="password">
      <input type="submit">
    </form>
    <table>
      <thead>
        <tr>
        <th>&emsp;&ensp;</th>
        <th><h2><l>Email</l></h2></th>
        <th>&emsp;&emsp;&emsp;&emsp;</th>
        <th><h2>Password</h2></th>
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
    if(isset($_POST["email"]) && isset($_POST["password"])) {
      if($_POST["email"] && $_POST["password"]) {
          $conn = mysqli_connect("sql3.freesqldatabase.com", "sql3402886", "gn4yJmWUfg", "sql3402886");
          $email = $_POST['email'];
          $password = $_POST['password'];
          if(!$conn) {
            die("Connection failed:" .mysqli_connect_error());
          }

          $sql = "SELECT password_encrypted FROM user WHERE email  = '$email'";
          $results = mysqli_query($conn, $sql);

          if($results) {
            $row = mysqli_fetch_assoc($results);
            $hash = $row["password_encrypted"];
            if(password_verify($password, $hash)) {
              $logged_in = true;
              $sql = "SELECT user_id FROM user WHERE email= '$email'";
                $results = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($results);
                $_SESSION['user_id'] = $row['user_id'];
                echo $_SESSION['user_id'];
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
