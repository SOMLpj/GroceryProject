<!-- start session, get session user id -->
<?php
    session_start();
?>

<html>
    <head><title>Submission</title></head>
    <body>
        <?php  
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $password_encrypted = password_hash($_POST["password_encrypted"],PASSWORD_DEFAULT);
            $phone = $_POST["phone"];
            $email = $_POST["email"];
            $retailer_id = "123";   
            $conn = mysqli_connect("sql3.freesqldatabase.com", "sql3402886", "gn4yJmWUfg","sql3402886");
            if (!$conn) {  
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "INSERT INTO user (email, first_name, last_name, password_encrypted, phone) VALUES ('$email','$first_name','$last_name','$password_encrypted','$phone')";
            $results = mysqli_query($conn, $sql);
            if ($results){
                echo "Great! You're registered.";
                $sql = "SELECT user_id FROM user WHERE email= '$email'";
                $results = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($results);
                $_SESSION['user_id'] = $row['user_id'];
                echo $_SESSION['user_id'];
            }
                
            else{
                echo"Uh-oh, something went wrong on our end.";
                echo mysqli_error($conn);
                mysqli_close($conn); 
            }
        ?>      
    </br>
    <a href="Registration.html">You're information was saved! Login</a>
    </body>
</html>
