<?php
include 'database.php';

// Get filter values
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$specialty = isset($_GET['specialty']) && $_GET['specialty'] !== 'All' ? $conn->real_escape_string($_GET['specialty']) : '';
$location = isset($_GET['location']) && $_GET['location'] !== 'All' ? $conn->real_escape_string($_GET['location']) : '';

// Base SQL query
$sql = "SELECT id, name, phone_number, specialty, location, rating, image, price, is_booked 
        FROM technicians 
        WHERE (name LIKE '%$search%' 
        OR phone_number LIKE '%$search%' 
        OR specialty LIKE '%$search%' 
        OR location LIKE '%$search%')";

// Apply Specialty Filter
if (!empty($specialty)) {
    $sql .= " AND specialty = '$specialty'";
}

// Apply Location Filter
if (!empty($location)) {
    $sql .= " AND location = '$location'";
}

$result = $conn->query($sql);

$response = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $availability = $row["is_booked"] == 0 ? "<span class='badge bg-success'>Available</span>" : "<span class='badge bg-danger'>N/A</span>";
        $imagePath = !empty($row["image"]) ? "backend/uploads/" . $row["image"] : "backend/uploads/default.jpg";

        $response .= "<tr>
                        <td>{$row["id"]}</td>
                        <td><img src='{$imagePath}' class='profile-img' /> {$row["name"]}</td>
                        <td>{$row["phone_number"]}</td>
                        <td>{$row["specialty"]}</td>
                        <td>{$row["location"]}</td>
                        <td>{$row["price"]}</td>
                        <td>{$row["rating"]}</td>
                        <td>{$availability}</td>
                        <td>
                            <a href='edit-technician.php?id={$row["id"]}' class='btn btn-primary btn-sm'>Edit</a>
                            <a href='delete-technician.php?id={$row["id"]}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                        </td>
                      </tr>";
    }
} else {
    $response = "<tr><td colspan='9' class='text-center'>No technicians found</td></tr>";
}

echo $response;
$conn->close();
?>
