<?php
date_default_timezone_set('UTC'); // Ensure consistent timezone
session_start();
require 'database.php'; // Database connection
require 'vendor/autoload.php'; // Include SendGrid SDK

use SendGrid\Mail\Mail;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['errors'] = ["Email not found."];
        header("Location: ../../forget_password.php");
        exit();
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $expiry = date('Y-m-d H:i:s', time() + 1800); // OTP expires in 30 minutes
    error_log("send_otp.php: Generated OTP = $otp, Expiry = $expiry"); // Debug log

    // Store OTP in the database
    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?");
    $stmt->bind_param("iss", $otp, $expiry, $email); // Fixed: "sis" to "iss"
    $stmt->execute();

    if ($stmt->error) {
        error_log("Failed to store OTP in database for email: $email, Error: " . $stmt->error);
        $_SESSION['errors'] = ["Failed to store OTP in the database. Error: " . $stmt->error];
        header("Location: ../../forget_password.php");
        exit();
    }

    if ($stmt->affected_rows == 0) {
        error_log("No rows updated for email: $email, possibly because the email does not exist.");
        $_SESSION['errors'] = ["Failed to store OTP in the database. No rows updated."];
        header("Location: ../../forget_password.php");
        exit();
    }

    error_log("Successfully stored OTP in database for email: $email");

    // Send OTP via email
    $emailMessage = new Mail();
    $emailMessage->setFrom("tanresourcesonlineservice@gmail.com", "Tan Resources Online Service");
    $emailMessage->setSubject("Your OTP for Password Reset");
    $emailMessage->addTo($email);
    $emailMessage->addContent("text/plain", "Your OTP for password reset is: $otp. It expires in 30 minutes.");

    $sendgrid = new \SendGrid(""); // Replace with your actual SendGrid API Key

    try {
        $start_time = microtime(true); // Record the start time
        $response = $sendgrid->send($emailMessage);
        $end_time = microtime(true); // Record the end time
        $duration = $end_time - $start_time; // Calculate the duration in seconds

        error_log("SendGrid: Email sent to $email, Status Code = " . $response->statusCode() . ", Duration = $duration seconds");

        if ($response->statusCode() == 202) {
            $_SESSION['email_to_verify'] = $email;
            header("Location: ../../verify_otp.php");
            exit();
        } else {
            error_log("SendGrid error: " . $response->statusCode() . " - " . $response->body());
            $_SESSION['errors'] = ["Failed to send OTP. Please try again."];
            header("Location: ../../forget_password.php");
            exit();
        }
    } catch (Exception $e) {
        error_log("SendGrid Exception: " . $e->getMessage());
        $_SESSION['errors'] = ["Mail error: " . $e->getMessage()];
        header("Location: ../../forget_password.php");
        exit();
    }
} else {
    header("Location: ../forget_password.php");
    exit();
}
?>