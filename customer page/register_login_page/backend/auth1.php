<?php
session_start();
require_once 'database.php'; // Adjust path if needed
require_once '../../../vendor/autoload.php'; // Adjust path if needed

use SendGrid\Mail\Mail;

function generateOTP() {
    return sprintf("%06d", mt_rand(0, 999999));
}

function sendOTP($email, $otp) {
    error_log("Attempting to send OTP to: $email");
    
    $apiKey = ''; // Your actual API key
    if (empty($apiKey) || $apiKey === 'YOUR_SENDGRID_API_KEY') {
        error_log("SendGrid Error: API key not configured");
        return false;
    }

    $sendgrid = new \SendGrid($apiKey);
    error_log("SendGrid object initialized");

    $emailObj = new Mail();
    $emailObj->setFrom("tanresourcesonlineservice@gmail.com", "TROS"); // Must be verified
    $emailObj->setSubject("Your Verification Code");
    $emailObj->addTo($email);
    $emailObj->addContent(
        "text/html",
        "<h3>Your OTP Code</h3><p>Your verification code is: <strong>$otp</strong></p><p>Valid for 10 minutes.</p>"
    );
    error_log("Email object configured");

    try {
        $response = $sendgrid->send($emailObj);
        $statusCode = $response->statusCode();
        $headers = $response->headers();
        $body = $response->body();
        error_log("SendGrid Response: Status $statusCode");
        error_log("Response Headers: " . implode("\n", $headers));
        if (!empty($body)) {
            error_log("Response Body: $body");
        }
        return $statusCode == 202;
    } catch (Exception $e) {
        error_log("SendGrid Exception: " . $e->getMessage());
        return false;
    }
}

if (isset($_POST['email_verify'])) {
    error_log("Email verification request received");
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = "Invalid email format";
        error_log("Invalid email format: $email");
        header("Location: ../email_entry.php");
        exit();
    }

    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['errors']['email'] = "Email already registered";
        error_log("Email already registered: $email");
        header("Location: ../email_entry.php");
        exit();
    }

    $otp = generateOTP();
    $otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    $token = bin2hex(random_bytes(32));

    $_SESSION['email_to_verify'] = $email;
    $_SESSION['temp_otp'] = $otp;
    $_SESSION['temp_otp_expiry'] = $otp_expiry;
    $_SESSION['temp_token'] = $token;
    error_log("Session data set. OTP: $otp");

    if (sendOTP($email, $otp)) {
        error_log("OTP sent successfully to $email");
        header("Location: ../otp_verify.php");
    } else {
        $_SESSION['errors']['email'] = "Failed to send OTP. Please try again.";
        error_log("Failed to send OTP to $email");
        header("Location: ../email_entry.php");
    }
    exit();
}

if (isset($_POST['otp_verify'])) {
    error_log("OTP verification request received");
    $otp = trim($_POST['otp']);

    if (!isset($_SESSION['temp_otp']) || $otp !== $_SESSION['temp_otp']) {
        $_SESSION['errors']['otp'] = "Invalid OTP";
        error_log("Invalid OTP submitted: $otp");
        header("Location: ../otp_verify.php");
        exit();
    }

    if (strtotime($_SESSION['temp_otp_expiry']) < time()) {
        $_SESSION['errors']['otp'] = "OTP has expired";
        error_log("OTP expired for email: " . $_SESSION['email_to_verify']);
        header("Location: ../otp_verify.php");
        exit();
    }

    // OTP is valid, mark email as verified and redirect to register
    $_SESSION['email_verified'] = true;
    error_log("OTP verified successfully for: " . $_SESSION['email_to_verify']);
    header("Location: ../register1.php");
    exit();
}

if (isset($_POST['complete_register'])) {
    if (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true) {
        header("Location: ../email_entry.php");
        exit();
    }

    $email = $_SESSION['email_to_verify'];
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    global $conn;
    // Insert user with status 'active' directly
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, otp_code, otp_expiry, status, verification_token, created_at) 
        VALUES (?, ?, ?, ?, ?, 'active', ?, NOW())");
    $stmt->bind_param("ssssss", 
        $full_name, 
        $email, 
        $password, 
        $_SESSION['temp_otp'],
        $_SESSION['temp_otp_expiry'],
        $_SESSION['temp_token']
    );

    if ($stmt->execute()) {
        // Clear temporary session data
        unset($_SESSION['email_to_verify']);
        unset($_SESSION['temp_otp']);
        unset($_SESSION['temp_otp_expiry']);
        unset($_SESSION['temp_token']);
        unset($_SESSION['email_verified']);
        
        error_log("Registration successful for: $email");
        // Redirect with success parameter to trigger pop-up
        header("Location: ../login.php?success=1");
        exit();
    } else {
        $_SESSION['errors']['register'] = "Registration failed: " . $conn->error;
        error_log("Registration failed for: $email - " . $conn->error);
        header("Location: ../register1.php");
        exit();
    }
}
?>