<?php
include '../database.php'; // Adjust path if necessary

$sql = "SELECT specialty, COUNT(*) as count FROM technicians WHERE is_booked = 0 GROUP BY specialty";
$result = $conn->query($sql);

$technicians = [];
while ($row = $result->fetch_assoc()) {
    $technicians[] = $row;
}

echo json_encode($technicians);
?>
