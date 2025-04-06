<?php
include 'booking/database.php'; // Ensure your database connection is correct

$sql = "SELECT * FROM feedback ORDER BY created_at DESC";
$result = $conn->query($sql);

$feedbacks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
} else {
    $feedbacks = ["message" => "No feedback available yet."];
}

header('Content-Type: application/json');
echo json_encode($feedbacks);
?>
