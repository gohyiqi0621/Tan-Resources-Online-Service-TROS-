<?php
session_start();
include '../booking/database.php'; // Update with correct database connection file

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_account'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Delete user from the database
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            // Destroy session and log out the user
            session_destroy();
            echo "Account deleted successfully.";
        } else {
            echo "Error deleting account.";
        }
        
        $stmt->close();
        $conn->close();
    } else {
        echo "User not logged in.";
    }
}
?>
