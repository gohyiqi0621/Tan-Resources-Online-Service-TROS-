<?php
$servername = "localhost"; // Change if using a remote server
$username = "root"; // Default XAMPP username, change if needed
$password = ""; // Default XAMPP password (empty), change if needed
$database = "tros"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding to avoid charset issues
$conn->set_charset("utf8mb4");
?>
