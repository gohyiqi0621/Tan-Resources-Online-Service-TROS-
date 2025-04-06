<?php
session_start();
require_once 'database.php'; // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = $_POST["transaction_id"] ?? '';
    $feedback = $_POST["feedback"] ?? '';
    $rating = $_POST["rating"] ?? 0; // Get the rating from the form
    $user_id = $_SESSION['user_id'] ?? 0; // Assuming user is logged in

    // Check if fields are empty
    if (empty($transaction_id) || empty($feedback) || $rating == 0) {
        echo "<script>alert('Please provide a rating and feedback!'); window.history.back();</script>";
        exit;
    }

    // Handle image upload if provided
    $image_path = null;
    if (isset($_FILES['feedback_image']) && $_FILES['feedback_image']['error'] == 0) {
        $upload_dir = 'uploads/feedback_images/'; // Set the upload directory
        $image_name = basename($_FILES['feedback_image']['name']);
        $image_path = $upload_dir . $image_name;
        
        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create the directory if not exists
        }

        // Move the uploaded image to the server
        if (!move_uploaded_file($_FILES['feedback_image']['tmp_name'], $image_path)) {
            echo "<script>alert('Failed to upload image. Please try again!'); window.history.back();</script>";
            exit;
        }
    }

    // Insert feedback, rating, and image path into the database
    $stmt = $conn->prepare("INSERT INTO feedback (transaction_id, user_id, feedback, rating, feedback_image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisds", $transaction_id, $user_id, $feedback, $rating, $image_path);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='../view_booking_customer.php';</script>";
    } else {
        echo "<script>alert('Failed to submit feedback. Try again!'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
