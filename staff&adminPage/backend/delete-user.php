<?php
session_start();
include 'database.php';

// Get previous page (fallback to index.php)
$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php';

// Ensure session role exists
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    echo "<script>alert('Access denied! Please log in as an admin.'); window.location.href='$previous_page';</script>";
    exit();
}

// Check if the user is an admin
if ($_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('Access denied! Only admins can delete customers.'); window.location.href='$previous_page';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the user
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Customer deleted successfully!'); window.location.href='../customer-list.php';</script>";
    } else {
        echo "<script>alert('Error deleting customer!'); window.location.href='../customer-list.php';</script>";
    }
    
    $stmt->close();
}

$conn->close();
?>
