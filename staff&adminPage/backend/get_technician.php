<?php
include 'database.php'; // Include the database connection

// Fetch technicians from the database
$sql = "SELECT id, name, phone_number, specialty, location, rating, image FROM technicians";
$result = $conn->query($sql);

$conn->close(); // Close connection after fetching data
?>
