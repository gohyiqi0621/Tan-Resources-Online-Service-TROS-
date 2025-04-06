<?php
include 'database.php'; // Ensure database connection

$query = isset($_GET['query']) ? trim($_GET['query']) : "";

if ($query !== "") {
    $stmt = $conn->prepare("
        SELECT 
            p.id, p.amount, p.status, p.technician_id, p.user_id, 
            t.name AS technician_name, t.rating, t.image AS technician_image, 
            u.name AS customer_name 
        FROM payments p
        LEFT JOIN technicians t ON p.technician_id = t.id
        LEFT JOIN users u ON p.user_id = u.id
        WHERE t.name LIKE ? OR u.name LIKE ?
        ORDER BY p.created_at DESC
    ");
    
    $searchParam = "%" . $query . "%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
} else {
    // Default query for 5 most recent orders
    $stmt = $conn->prepare("
        SELECT 
            p.id, p.amount, p.status, p.technician_id, p.user_id, 
            t.name AS technician_name, t.rating, t.image AS technician_image, 
            u.name AS customer_name 
        FROM payments p
        LEFT JOIN technicians t ON p.technician_id = t.id
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT 5
    ");
}

$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
