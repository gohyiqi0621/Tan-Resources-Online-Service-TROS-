<?php
require 'database.php';

$query = "SELECT id, name, email, profile_picture FROM users WHERE role = 'customer' ORDER BY created_at DESC LIMIT 7";
$result = $conn->query($query);

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($customers);
?>
