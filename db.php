<?php
$host = "sql111.infinityfree.com";
$user = "if0_40180122";
$pass = "MOR078601";
$dbname = "if0_40180122_epiz_12345678_my_newdb";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
