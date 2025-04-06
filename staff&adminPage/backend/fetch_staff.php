<?php
include 'database.php';

// Get search input and role filter
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
$role = isset($_GET['role']) && $_GET['role'] != '' ? $_GET['role'] : "%";

// Only allow filtering by 'admin' or 'staff'
$allowed_roles = ['admin', 'staff'];
if (!in_array($role, $allowed_roles) && $role !== "%") {
    $role = "%"; 
}

// SQL query: **Always filter to show only 'admin' and 'staff'**
$sql = "SELECT id, name, phone_number, email, role, profile_picture 
        FROM users 
        WHERE role IN ('admin', 'staff') 
        AND (role = ? OR ? = '%') 
        AND (name LIKE ? OR email LIKE ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $role, $role, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$counter = 1; // Start numbering from 1

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$counter}</td>"; // ID starts from 1
        echo "<td>";
        if (!empty($row["profile_picture"])) {
            echo "<img src='uploads/{$row["profile_picture"]}' class='profile-img' />";
        } else {
            echo "<img src='user-profile-default.webp' class='profile-img' />";
        }
        echo "<span class='profile-name'>{$row['name']}</span>";
        echo "</td>";
        echo "<td>{$row['phone_number']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['role']}</td>";
        echo "<td>
                <a href='edit-staff.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a>
                <a href='delete-staff.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
              </td>";
        echo "</tr>";
        $counter++; // Increment counter for next row
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
}

$conn->close();
?>
