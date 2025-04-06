<?php
$host = "localhost";
$dbname = "tros";
$username = "root";
$password = "";

// Create connection using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
