<?php
// $servername = "sql310.infinityfree.com";
// $username = "if0_35372784";
// $password = "Lr2kLeE83J4my";
// $db_name="if0_35372784_inventory";



$servername = "127.0.0.1";
$username = "root";
$password = "";
$db_name="inventory";


// Create connection
$conn = mysqli_connect($servername, $username, $password,$db_name);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
session_start();
$target_dir = "../assests/img/";
?>