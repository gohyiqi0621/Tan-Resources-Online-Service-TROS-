<?php
include '../database.php';

$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Monthly Sales Data (unchanged)
$sql = "SELECT MONTH(booking_date) as month, SUM(amount) as total 
        FROM payments 
        WHERE status = 'Completed' AND YEAR(booking_date) = '$year'";
if (!empty($month)) {
    $sql .= " AND MONTH(booking_date) = '$month'";
}
$sql .= " GROUP BY MONTH(booking_date)";

$result = $conn->query($sql);
$monthlyData = array_fill(1, 12, 0);
while ($row = $result->fetch_assoc()) {
    $monthlyData[(int)$row['month']] = (float)$row['total'];
}

// Service Type Distribution Data (replacing status distribution)
$serviceTypeSql = "SELECT service_type, COUNT(*) as count 
                   FROM payments 
                   WHERE YEAR(booking_date) = '$year'";
if (!empty($month)) {
    $serviceTypeSql .= " AND MONTH(booking_date) = '$month'";
}
$serviceTypeSql .= " GROUP BY service_type";

$serviceTypeResult = $conn->query($serviceTypeSql);
$serviceTypeData = [
    'labels' => [],
    'values' => []
];
while ($row = $serviceTypeResult->fetch_assoc()) {
    $serviceTypeData['labels'][] = $row['service_type'];
    $serviceTypeData['values'][] = (int)$row['count'];
}

// Prepare the JSON response
$data = [
    'monthly' => [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        'values' => array_values($monthlyData)
    ],
    'service_type' => $serviceTypeData
];

header('Content-Type: application/json');
echo json_encode($data);
?>