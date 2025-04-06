<?php
session_start();
include_once '../assets/database/database1/database.php';

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch current user data from the database
    $query = "SELECT name, email, phone_number, address, gender, profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_name, $current_email, $current_phone, $current_address, $current_gender, $current_profile);
    $stmt->fetch();
    $stmt->close();

    // Preserve existing values if input fields are empty
    $name = isset($_POST['name']) && $_POST['name'] !== "" ? $_POST['name'] : $current_name;
    $email = isset($_POST['email']) && $_POST['email'] !== "" ? $_POST['email'] : $current_email;
    $phone_number = isset($_POST['phone_number']) && $_POST['phone_number'] !== "" ? $_POST['phone_number'] : $current_phone;
    $address = isset($_POST['address']) && $_POST['address'] !== "" ? $_POST['address'] : $current_address;
    $gender = isset($_POST['gender']) && $_POST['gender'] !== "" ? $_POST['gender'] : $current_gender;
    $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    
    $profile_picture = $current_profile; // Keep existing profile picture if not updated

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['profile_picture']['tmp_name'];
        $profile_filename = uniqid() . '_' . $_FILES['profile_picture']['name'];
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        move_uploaded_file($tmp_name, $upload_dir . $profile_filename);
        $profile_picture = $profile_filename; // Update only if a new file is uploaded
    }

    // Prepare SQL query
    $sql = "UPDATE users SET name = ?, email = ?, phone_number = ?, address = ?, gender = ?";
    $params = [$name, $email, $phone_number, $address, $gender];

    if ($profile_picture !== null) {
        $sql .= ", profile_picture = ?";
        $params[] = $profile_picture;
    }

    if ($password !== null) {
        $sql .= ", password = ?";
        $params[] = $password;
    }

    $sql .= " WHERE id = ?";
    $params[] = $user_id;

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters dynamically
    $types = str_repeat("s", count($params) - 1) . "i"; // All are strings except user_id (integer)
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: viewProfile.php");
        exit();
    } else {
        echo "No changes made.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
