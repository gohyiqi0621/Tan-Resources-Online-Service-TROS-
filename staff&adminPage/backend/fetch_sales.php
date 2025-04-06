<?php
include 'database.php'; // Adjust based on your file structure

$month = $_GET['month'] ?? 'All';
$year = $_GET['year'] ?? date("Y");
$status = $_GET['status'] ?? 'All';
$service_type = $_GET['service_type'] ?? 'All';

$query = "SELECT transaction_id, amount, booking_date, service_type, status FROM payments WHERE YEAR(booking_date) = ?";

$params = [$year];
$types = "i"; 

if ($month !== "All") {
    $query .= " AND MONTH(booking_date) = ?";
    $params[] = date("m", strtotime("1 $month"));
    $types .= "i";
}
if ($status !== "All") {
    $query .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}
if ($service_type !== "All") {
    $query .= " AND service_type = ?";
    $params[] = $service_type;
    $types .= "s";
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['transaction_id']}</td>
        <td>{$row['amount']}</td>
        <td>" . date('Y-m-d', strtotime($row['booking_date'])) . "</td>
        <td>{$row['service_type']}</td>
        <td>{$row['status']}</td>
    </tr>";
}
$stmt->close();
$conn->close();
?>
