<?php
$db_host = 'localhost';
$db_name = 'mon_projet';
$db_user = 'root';
$db_password = '';




$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mon_projet";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
