<?php
include '../database.php';

// Query to calculate average rating per specialty
$sql = "
    SELECT 
        CASE 
            WHEN t.specialty = 'Renovation' THEN 'Renovations' 
            ELSE t.specialty 
        END AS specialty, 
        COUNT(f.id) AS total_feedbacks, 
        COALESCE(AVG(f.rating), 0) AS avg_rating
    FROM technicians t
    LEFT JOIN payments p ON t.id = p.technician_id
    LEFT JOIN feedback f ON p.transaction_id = f.transaction_id
    WHERE f.rating IS NOT NULL
    GROUP BY specialty
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['specialty']] = [
        'total_feedbacks' => $row['total_feedbacks'],
        'avg_rating' => number_format($row['avg_rating'], 2) // Format to 2 decimal places
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
