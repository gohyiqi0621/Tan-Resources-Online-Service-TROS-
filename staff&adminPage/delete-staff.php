<?php
session_start();
include 'backend/database.php'; // Ensure database connection is included

// Debugging: Print role to confirm it's correct
if (!isset($_SESSION['user_role'])) {
    die("❌ No user_role found! Please log in.");
} elseif ($_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('❌ Only admin can access this page!'); window.location.href = 'index.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    // Delete technician from database
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Reorder IDs after deletion
        mysqli_query($conn, "SET @count = 0");
        mysqli_query($conn, "UPDATE users SET id = (@count:=@count+1) ORDER BY id");
        mysqli_query($conn, "ALTER TABLE users AUTO_INCREMENT = 1");

        echo "<script>
            alert('Staff deleted successfully! ID reordered.');
            window.location.href = 'staff-list.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to delete staff.');
            window.location.href = 'staff-list.php';
        </script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "<script>
        alert('Invalid request.');
        window.location.href = 'staff-list.php';
    </script>";
}

?>
