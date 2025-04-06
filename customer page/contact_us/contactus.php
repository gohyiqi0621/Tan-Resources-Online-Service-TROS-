<?php
// Database connection
require_once 'database.php';

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$mobile_no = $_POST['mobile_no'] ?? '';
$reference = $_POST['reference'] ?? '';
$service = $_POST['service'] ?? '';
$message = $_POST['message'] ?? '';

// Save to database using PDO
$sql = "INSERT INTO contact_form_submissions (name, email, mobile_no, reference, service, message)
        VALUES (:name, :email, :mobile_no, :reference, :service, :message)";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':name', $name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':mobile_no', $mobile_no);
$stmt->bindParam(':reference', $reference);
$stmt->bindParam(':service', $service);
$stmt->bindParam(':message', $message);

if ($stmt->execute()) {
    // Redirect to index.html with a popup message
    echo "<script>
            alert('Review Sent Successfully');
            window.location.href = '../index.html';
          </script>";
} else {
    echo "Error: " . $stmt->errorInfo()[2];  // Get error details
}
?>
