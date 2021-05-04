<?php 

function pdo_connect_mysql() {
    $host = "sql3.freesqldatabase.com:3306";
    $username = "sql3402886";
    $password = "gn4yJmWUfg";

    $db_name = "sql3402886";

    try {
        return new PDO('mysql:host='.$host.';dbname='.$db_name, $username, $password);
    }
   catch (PDOException $exception) {
       exit('Failed to connect to database!');
   }
}
?>