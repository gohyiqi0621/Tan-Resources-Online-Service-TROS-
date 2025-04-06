<?php
// Start session (if needed for user authentication)
session_start();

// Include database connection
require 'database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log the start of the script
error_log('add_hardware.php script started');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log('Invalid request method');
    die(json_encode(['success' => false, 'message' => 'Error: Invalid request method.']));
}

// Log form data
error_log('POST data: ' . print_r($_POST, true));
error_log('FILES data: ' . print_r($_FILES, true));

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$short_description = isset($_POST['short_description']) ? trim($_POST['short_description']) : null;
$long_description = isset($_POST['long_description']) ? trim($_POST['long_description']) : null;
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$rating = isset($_POST['rating']) ? floatval($_POST['rating']) : null;
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
$color = isset($_POST['color']) ? trim($_POST['color']) : null;

// Validate required fields
if (empty($name)) {
    error_log('Validation failed: Product name is required');
    die(json_encode(['success' => false, 'message' => 'Error: Product name is required.']));
}
if (empty($type)) {
    error_log('Validation failed: Category is required');
    die(json_encode(['success' => false, 'message' => 'Error: Category is required.']));
}
if ($price < 0) {
    error_log('Validation failed: Price cannot be negative');
    die(json_encode(['success' => false, 'message' => 'Error: Price cannot be negative.']));
}

// Validate and handle file upload
$picture = null;
$upload_dir = "../staff&adminPage/backend/hardware/"; // Updated directory
if (!file_exists($upload_dir)) {
    error_log('Creating upload directory: ' . $upload_dir);
    if (!mkdir($upload_dir, 0777, true)) {
        error_log('Failed to create upload directory');
        die(json_encode(['success' => false, 'message' => 'Error: Failed to create upload directory.']));
    }
}

if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['picture']['tmp_name'];
    $file_name = $_FILES['picture']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png'];

    if (!in_array($file_ext, $allowed_extensions)) {
        error_log('Validation failed: Invalid file extension');
        die(json_encode(['success' => false, 'message' => 'Error: Only JPG, JPEG, and PNG files are allowed.']));
    }

    $max_size = 5 * 1024 * 1024; // 5MB in bytes
    if ($_FILES['picture']['size'] > $max_size) {
        error_log('Validation failed: File size exceeds 5MB');
        die(json_encode(['success' => false, 'message' => 'Error: File size exceeds 5MB limit.']));
    }

    $new_file_name = uniqid('img_') . '.' . $file_ext;
    $file_path = $upload_dir . $new_file_name;

    error_log('Attempting to move file to: ' . $file_path);
    if (!move_uploaded_file($file_tmp, $file_path)) {
        error_log('Failed to move uploaded file');
        die(json_encode(['success' => false, 'message' => 'Error: Failed to upload image.']));
    }

    $picture = $new_file_name;
} else {
    error_log('Validation failed: Image upload required');
    die(json_encode(['success' => false, 'message' => 'Error: Image upload is required.']));
}

// Validate rating
if ($rating !== null) {
    if ($rating < 0 || $rating > 5) {
        error_log('Validation failed: Rating out of range');
        die(json_encode(['success' => false, 'message' => 'Error: Rating must be between 0 and 5.']));
    }
    $rating = round($rating, 2);
}

// Round price to 2 decimal places
$price = round($price, 2);

// Tags (not in the form, set to NULL)
$tags = null;

// Prepare SQL statement
$sql = "INSERT INTO technical_hardware (name, type, colour, rating, short_description, long_description, picture, tags, price) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log('SQL prepare failed: ' . $conn->error);
    die(json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]));
}

// Bind parameters
$stmt->bind_param(
    "sssdsdssd",
    $name,
    $type,
    $color,
    $rating,
    $short_description,
    $long_description,
    $picture,
    $tags,
    $price
);

// Execute the statement
if ($stmt->execute()) {
    error_log('Product added successfully');
    die(json_encode(['success' => true, 'message' => 'Product added successfully!']));
} else {
    error_log('SQL execute failed: ' . $stmt->error);
    die(json_encode(['success' => false, 'message' => 'Error adding product: ' . $stmt->error]));
}

// Clean up
$stmt->close();
$conn->close();
?>