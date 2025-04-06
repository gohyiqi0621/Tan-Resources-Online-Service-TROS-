<?php
session_start();

// Include the database connection
require '../database.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['errors'] = ['Both password fields are required.'];
        header("Location: ../../reset_password.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['errors'] = ['Passwords do not match.'];
        header("Location: ../../reset_password.php");
        exit();
    }

    if (strlen($new_password) < 8) {
        $_SESSION['errors'] = ['Password must be at least 8 characters long.'];
        header("Location: ../../reset_password.php");
        exit();
    }

    // Fetch the user's current password from the database
    $query = "SELECT password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['errors'] = ['No account found with this email.'];
        header("Location: ../../reset_password.php");
        exit();
    }

    $user = $result->fetch_assoc();
    $current_hashed_password = $user['password'];

    // Check if the new password is the same as the current password
    if (password_verify($new_password, $current_hashed_password)) {
        $_SESSION['errors'] = ['New password cannot be the same as the previous password.'];
        header("Location: ../../reset_password.php");
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_query = "UPDATE users SET password = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ss", $hashed_password, $email);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        // Password updated successfully
        // Clear session variables
        unset($_SESSION['otp_verified']);
        unset($_SESSION['email_to_reset']);

        // Redirect to login page with success message
        $_SESSION['success'] = 'Password reset successfully. Please log in with your new password.';
        header("Location: ../../login.php");
        exit();
    } else {
        // Failed to update password
        $_SESSION['errors'] = ['Failed to reset password. Please try again.'];
        header("Location: ../../reset_password.php");
        exit();
    }
} else {
    // If the page is accessed directly without form submission, redirect to reset password page
    header("Location: ../../reset_password.php");
    exit();
}
?>