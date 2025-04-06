<?php
date_default_timezone_set('UTC'); // Ensure consistent timezone
session_start();

// Include the database connection
require 'database.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $email = isset($_SESSION['email_to_verify']) ? trim($_SESSION['email_to_verify']) : '';

    // Validate inputs
    if (empty($otp)) {
        $_SESSION['errors'] = ['Please enter the OTP.'];
        header("Location: ../../verify_otp.php");
        exit();
    }

    if (empty($email)) {
        $_SESSION['errors'] = ['Email not found in session. Please start the process again.'];
        header("Location: ../../forget_password.php");
        exit();
    }

    // Fetch the user from the database based on the email
    $query = "SELECT otp_code, otp_expiry FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['errors'] = ['No account found with this email.'];
        header("Location: ../../verify_otp.php");
        exit();
    }

    $user = $result->fetch_assoc();
    $stored_otp = $user['otp_code'];
    $otp_expiry = $user['otp_expiry'];

    // Debug: Log the OTP and expiry values
    error_log("check_otp.php: Stored OTP = $stored_otp, OTP Expiry = $otp_expiry, Current Time = " . (new DateTime())->format('Y-m-d H:i:s'));

    // Check if otp_expiry is invalid (e.g., '0000-00-00 00:00:00')
    if ($otp_expiry === '0000-00-00 00:00:00') {
        $_SESSION['errors'] = ['Invalid OTP expiry. Please request a new OTP.'];
        header("Location: ../../verify_otp.php");
        exit();
    }

    // Check if OTP is expired
    $current_time = new DateTime();
    $expiry_time = new DateTime($otp_expiry);
    if ($current_time > $expiry_time) {
        $_SESSION['errors'] = ['The OTP has expired. Please request a new one.'];
        header("Location: ../../verify_otp.php");
        exit();
    }

    // Verify the OTP
    if ($otp === $stored_otp) {
        // OTP is correct, clear the OTP fields in the database
        $update_query = "UPDATE users SET otp_code = NULL, otp_expiry = NULL WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("s", $email);
        $update_stmt->execute();

        // Set a session variable to indicate OTP verification success
        $_SESSION['otp_verified'] = true;
        $_SESSION['email_to_reset'] = $email; // Store email for the reset password step

        // Redirect to reset password page
        header("Location: ../../reset_password.php");
        exit();
    } else {
        // OTP is incorrect
        $_SESSION['errors'] = ['Invalid OTP. Please try again.'];
        header("Location: ../../verify_otp.php");
        exit();
    }
} else {
    // If the page is accessed directly without form submission, redirect to verify OTP page
    header("Location: ../../verify_otp.php");
    exit();
}
?>