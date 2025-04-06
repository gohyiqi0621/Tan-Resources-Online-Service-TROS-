<?php
include 'database.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=technicians.csv');

$output = fopen('php://output', 'w');

// Add column headers
fputcsv($output, ['ID', 'Technician', 'Phone Number', 'Specialty', 'Location', 'Price', 'Rating', 'Availability']);

$sql = "SELECT id, name, phone_number, specialty, location, price, rating, is_booked FROM technicians";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Format availability
        $availability = ($row['is_booked'] == 0) ? 'Available' : 'N/A';

        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['phone_number'],
            $row['specialty'],
            $row['location'],
            $row['price'],
            $row['rating'],
            $availability
        ]);
    }
}

fclose($output);
$conn->close();
?>
