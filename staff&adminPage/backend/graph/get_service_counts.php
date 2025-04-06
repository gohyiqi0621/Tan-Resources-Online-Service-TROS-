<?php
session_start();
include '../database.php'; // Ensure database connection

header('Content-Type: application/json');

$response = [
    'plumbing' => 0,
    'renovation' => 0,
    'electrical' => 0
];

// Fetch count of each service type
$stmt = $conn->prepare("SELECT service_type, COUNT(*) AS count FROM payments WHERE status = 'Completed' GROUP BY service_type");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $service_type = strtolower($row['service_type']); // Convert to lowercase for consistency
    if (isset($response[$service_type])) {
        $response[$service_type] = (int)$row['count']; // Ensure it's an integer
    }
}

echo json_encode($response);
exit;
?>
