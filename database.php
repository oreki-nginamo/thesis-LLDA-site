<?php
$servername = "localhost";
$user = "root";
$pass = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=llda_db", $user, $pass);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected";

}catch(PDOException $e){



   $errorMsg=  $e->getMessage();
   echo '<script type="text/javascript">alert("INFO:  '.$errorMsg.'");</script>';
      }



?>