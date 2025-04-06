<?php
include '../database.php';

$query = "SELECT DATE_FORMAT(booking_date, '%b') AS month, COUNT(id) AS total_orders 
          FROM payments 
          WHERE status = 'Completed' AND booking_date IS NOT NULL
          GROUP BY month 
          ORDER BY MONTH(booking_date)";

$result = mysqli_query($conn, $query);

// Define all months (to ensure missing months appear as 0)
$all_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$orders_map = array_fill_keys($all_months, 0);

while ($row = mysqli_fetch_assoc($result)) {
    $orders_map[$row['month']] = (int) $row['total_orders'];
}

// Ensure data is in the correct order
$data = [
    'months' => array_keys($orders_map),
    'orders' => array_values($orders_map)
];

echo json_encode($data);
?>
