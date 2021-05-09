<?php
    // echo $_SESSION["logged_in"];
    // if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true){
    //     header("location: ../front_page/front_page.php");
    //     echo "alert('already login')";
    //     exit;
    // }
?>

<html>
    <link rel="stylesheet" href="styles.css">
    <head><title>Registration</title></head>
        <body style = "background-image: -moz-linear-gradient(aliceblue, white); height:890px;">
        <h3 style ="text-align:center; padding-top:50px; font-size: 60; font-family:Verdana, Geneva, Tahoma, sans-serif; margin-bottom: 50;"> Spartan Fresh</h3>
        <img src="cart.png" alt="Grocery Cart" class = "pic">
        <h3 style ="text-align:center; font-size: 40; font-family:Verdana, Geneva, Tahoma, sans-serif;"> Sit back, we'll take it from here.</h3>
        <form action="AccountCreation.php" method="post" class = "input" style="margin: 50 auto;"> 
        <input type="text" onpaste="return false;" ondrop="return false;" autocomplete="off" name="first_name" placeholder="First name" required / style =" height:30; width: 200;" class ="form">         
        <input type="text" onpaste="return false;" ondrop="return false;" autocomplete="off" name="last_name" placeholder="Last name"  required /style="margin-top: 10; height:30; width: 200;"class ="form">   
        <input type="password" onpaste="return false;" ondrop="return false;" autocomplete="off" name="password_encrypted" placeholder="Password" required / style="margin-top: 10;  height:30; width: 200;"class ="form">  
        <input type="email" onpaste="return false;" ondrop="return false;" autocomplete="off" name="email" placeholder= "Email: email@domain.com"   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required / style="margin-top: 10;  height:30; width: 200;"" class = "form">
        <input type="tel" onpaste="return false;" ondrop="return false;" autocomplete="off" name="phone" placeholder="Number: ### ### ####"  pattern="[0-9]{3} [0-9]{3} [0-9]{4}" maxlength="12" required / style="margin-top: 10;  height:30; width: 200;"class ="form">  
        <input type="submit" value ="Sign up" style="margin-top: 20; margin-bottom: 20; border-radius: 20px; height:30; width: 150;"class ="button">
        <div class="tooltip"><i>Why do I need to provide my phone number?</i>
            <span class="tooltiptext" style = "text-align: center;">Providing your phone number helps us deliver to you on time if we have trouble locating your house/ apartment complex.</span>
        </div> 
        </form>   
    </body> 
</html>