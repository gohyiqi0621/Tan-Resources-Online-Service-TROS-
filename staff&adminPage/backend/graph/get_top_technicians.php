<?php
include '../database.php';

// Fetch top-rated technician per specialty, ensuring all specialties are included
$sql = "
    SELECT t.id AS technician_id, t.name AS technician_name, 
           CASE 
               WHEN t.specialty = 'Renovation' THEN 'Renovations' 
               ELSE t.specialty 
           END AS specialty, 
           COUNT(f.id) AS total_feedbacks, 
           COALESCE(AVG(f.rating), 0) AS avg_rating
    FROM technicians t
    LEFT JOIN payments p ON t.id = p.technician_id
    LEFT JOIN feedback f ON p.transaction_id = f.transaction_id
    GROUP BY t.id, specialty
    ORDER BY avg_rating DESC, total_feedbacks DESC
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
