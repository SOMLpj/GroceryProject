<?php
    session_start();
?>

<html>
<link rel="stylesheet" href="styles.css">
</html>

<!-- SESSION STUFF LATER -->
<!-- <?php
    $user_id = $_SESSION['user_id']; //store in array
    
?> -->

<body style = "background-color: rgba(248, 245, 242, 0.1);">

<div style = "padding-top: 50; display: flex; justify-content: center;">
<?php     
    $conn = mysqli_connect("sql3.freesqldatabase.com", "sql3402886", "gn4yJmWUfg","sql3402886");
    if (!$conn) {  
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT first_name FROM user WHERE email = 'rockykandah@gmail.com'";
    $result = mysqli_query($conn, $sql);
    $first_name = mysqli_fetch_assoc($result)['first_name'];

    $sql = "SELECT last_name FROM user WHERE email = 'rockykandah@gmail.com'";
    $result = mysqli_query($conn, $sql);
    $last_name =  mysqli_fetch_assoc($result)['last_name'];

    $sql = "SELECT phone FROM user WHERE email = 'rockykandah@gmail.com'";
    $result = mysqli_query($conn, $sql);
    $phone =  mysqli_fetch_assoc($result)['phone'];

    $sql = "SELECT email FROM user WHERE email = 'rockykandah@gmail.com'";
    $result = mysqli_query($conn, $sql);
    $email =  mysqli_fetch_assoc($result)['email'];

    echo "<div class=\"input\">";
    echo"<p class=\"font\">";
    echo "<b>" . $first_name;
    echo " " . $last_name;
    echo "<br>";
    echo "<br>";    
    echo $email;
    echo "<br>";
    echo "<br>";
    echo $phone . "</b>";
    echo "</p>";
    echo "</div>";
?>   
</div>
</body>