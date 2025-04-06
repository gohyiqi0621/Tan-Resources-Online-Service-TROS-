<?php
include 'database.php';

$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$status = isset($_GET['status']) ? $_GET['status'] : '';
$service_type = isset($_GET['service_type']) ? $_GET['service_type'] : '';

$sql = "SELECT SUM(amount) AS total_sales FROM payments WHERE status = 'Completed'";

if (!empty($month)) {
    $sql .= " AND MONTH(booking_date) = '$month' AND YEAR(booking_date) = '$year'";
}

if (!empty($service_type)) {
    $sql .= " AND service_type = '$service_type'";
}

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalSales = $row['total_sales'] ?? 0;

echo "<tr>
        <td colspan='4' class='text-end'><strong>Total Sales for Selected Month:</strong></td>
        <td><strong>RM " . number_format($totalSales, 2) . "</strong></td>
      </tr>";
?>
