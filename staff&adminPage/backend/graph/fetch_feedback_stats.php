<?php
include '../database.php'; // Ensure correct database connection

// Query to count transactions by service type where status is 'Completed'
$query = "
    SELECT 
        SUM(CASE WHEN service_type = 'plumbing' THEN 1 ELSE 0 END) AS plumbing_count,
        SUM(CASE WHEN service_type = 'renovation' THEN 1 ELSE 0 END) AS renovation_count,
        SUM(CASE WHEN service_type = 'electrical' THEN 1 ELSE 0 END) AS electrical_count,
        COUNT(*) AS total_count
    FROM payments
    WHERE payment_status = 'paid' AND status = 'Completed'"; // Filter only 'Completed' status

$result = $conn->query($query);
$data = $result->fetch_assoc();

// Avoid division by zero
$total = max($data['total_count'], 1);

$response = [
    'plumbing' => round(($data['plumbing_count'] / $total) * 100, 2),
    'renovation' => round(($data['renovation_count'] / $total) * 100, 2),
    'electrical' => round(($data['electrical_count'] / $total) * 100, 2)
];

header('Content-Type: application/json');
echo json_encode($response);
?>
