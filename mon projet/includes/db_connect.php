<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mon_projet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>