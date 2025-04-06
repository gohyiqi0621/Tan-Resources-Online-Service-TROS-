<?php
// backend/export_sales.php
require 'database.php'; // Adjust path as needed

// Get filter parameters
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$service_type = isset($_GET['service_type']) ? $_GET['service_type'] : '';

// Check if the year is 2024
if ($year === '2024') {
    echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
    exit;
}

// Build the SQL query
$query = "SELECT * FROM payments WHERE 1=1";
$params = [];
$types = '';

if (!empty($month)) {
    $query .= " AND MONTH(booking_date) = ?";
    $params[] = $month;
    $types .= 's';
}

if (!empty($year)) {
    $query .= " AND YEAR(booking_date) = ?";
    $params[] = $year;
    $types .= 's';
}

if (!empty($status)) {
    $query .= " AND status = ?";
    $params[] = $status;
    $types .= 's';
}

if (!empty($service_type)) {
    $query .= " AND service_type = ?";
    $params[] = $service_type;
    $types .= 's';
}

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Determine the status class
        $status_class = '';
        switch (strtolower($row['status'])) {
            case 'canceled':
                $status_class = 'status-canceled';
                break;
            case 'completed':
                $status_class = 'status-completed';
                break;
            case 'pending':
                $status_class = 'status-pending';
                break;
            default:
                $status_class = ''; // No class for unknown status
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
        echo "<td>MYR " . number_format($row['amount'], 2) . "</td>";
        echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['service_type']) . "</td>";
        echo "<td class='$status_class'>" . htmlspecialchars($row['status']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
}

$stmt->close();
$conn->close();
?>