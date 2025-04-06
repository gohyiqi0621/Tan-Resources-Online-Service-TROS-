<?php
session_start();
require_once 'db_conn.php'; // Database connection

$errors = [];

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        $errors['login'] = 'Email not found';
        error_log("Login failed: Email not found - $email");
    } elseif (!password_verify($password, $user['password'])) {
        $errors['login'] = 'Invalid email or password';
        error_log("Login failed: Incorrect password - $email");
    } elseif ($user['status'] !== 'active') {
        $errors['login'] = 'Account is not active. Please complete registration or verify your email.';
        error_log("Login failed: Account not active - $email");
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../login.php'); // Redirect to login page if error
        exit();
    }

    // Set session and role-based redirection
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role']; // Set role in session
    $_SESSION['email'] = $user['email']; // Fixed typo: use $user['email'] instead of $user_email

    // Redirect based on user role
    if ($_SESSION['user_role'] === 'customer') {
        header('Location: ../../index.html'); // Redirect to customer page
    } elseif ($_SESSION['user_role'] === 'staff') {
        header('Location: ../../../staff&adminPage/index.php'); // Redirect to staff page
    } elseif ($_SESSION['user_role'] === 'admin') {
        header('Location: ../../../staff&adminPage/index.php'); // Redirect to admin page
    }

    error_log("Login successful for: $email");
    exit();
}

$_SESSION['errors']['login'] = 'Invalid request';
header('Location: ../login.php');
exit();
?>