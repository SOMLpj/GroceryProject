<?php
  //Start session
  session_start();
  //Set dedault timezone
  date_default_timezone_set("America/Los_Angeles");
 ?>
<html>
  <head>
    <title>Place Order</title>
  </head>
  <body>
    <?php
      //create connection
      $conn = mysqli_connect("sql3.freesqldatabase.com:3306", "sql3402886", "gn4yJmWUfg", "sql3402886");
      //check connection
      if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
      }
      if (isset($_POST["paymentS"]) && isset($_POST["addressS"]) && isset($_POST["dateS"])){
        if ($_POST["paymentS"] && $_POST["addressS"] && $_POST["dateS"]){
          $paymentName = $_POST["paymentS"];
          $addressName = $_POST["addressS"];
          $dateName = $_POST["dateS"];
          $orderDate = date("Y-m-d H:i:s");

          //Test only
          echo "<br>";
          echo $orderDate;
          echo "<br>";
          echo $paymentName;
          echo "<br>";
          echo $addressName;
          echo "<br>";
          echo $dateName;
          echo "<br>";

          //Place order
          $sql1 = "INSERT INTO customer_order (FK_customer_id, order_date, order_total, FK_status_id, order_address, order_payment, delivery_date) VALUES ('1', '$orderDate', '100.00', '1', '$addressName', '$paymentName', '$dateName')";
          $results1 = mysqli_query($conn, $sql1);
          if ($results1) {
            echo "Order Has Been Placed!";
            $thisOrderId = mysqli_insert_id($conn);
          }
          else {
            echo mysqli_error($conn);
          }
          echo "<br>";
          //Update stock
          $sql2 = "UPDATE product SET stock = stock - 10 WHERE product_id = '1'";
          $results2 = mysqli_query($conn, $sql2);
          if ($results2){
            echo "(Test only) Update stock";
          }
          echo "<br>";
          //Update order item
          $sql3 = "INSERT INTO order_item (FK_order_id, FK_product_id, quantity) VALUES ('$thisOrderId', '1', '10')";
          $results3 = mysqli_query($conn, $sql3);
          if ($results2){
            echo "(Test only) Update Order Item";
          }
          mysqli_close($conn); //close connection
        }
        else {
          echo "Nothing selected";
        }
      }
      //Add to order

    ?>
  </body>
</html>
