<?php
include 'database.php'; // Include database connection

if (isset($_POST['edit_staff'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Fetch existing image from 'users' table
    $query = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        // Display error pop-up and redirect
        echo "<script>
                alert('Error preparing statement: " . mysqli_error($conn) . "');
                window.location.href = '../staff-list.php';
              </script>";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $current_image = $user['profile_picture'] ?? ''; // Handle case where no image exists
    mysqli_stmt_close($stmt);

    // Image Upload Handling
    if (!empty($_FILES['profile_picture']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $image_name = $_FILES['profile_picture']['name'];
        $image_tmp = $_FILES['profile_picture']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (in_array($image_ext, $allowed_extensions)) {
            $upload_dir = "../uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_new_name = uniqid() . "_" . basename($image_name);
            $target = $upload_dir . $image_new_name;

            if (move_uploaded_file($image_tmp, $target)) {
                // Delete old image if a new one is uploaded
                $old_image_path = $upload_dir . $current_image;
                if (!empty($current_image) && file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
                $current_image = $image_new_name; // Set new image name
            } else {
                // Display error pop-up for image upload failure
                echo "<script>
                        alert('Failed to upload the image. Please try again.');
                        window.location.href = '../staff-list.php';
                      </script>";
                exit();
            }
        } else {
            // Display error pop-up for invalid file type
            echo "<script>
                    alert('Invalid file type. Allowed types: jpg, jpeg, png, gif, webp.');
                    window.location.href = '../staff-list.php';
                  </script>";
            exit();
        }
    }

    // Update the user details (excluding password)
    $query = "UPDATE users SET name = ?, email = ?, phone_number = ?, role = ?, profile_picture = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        // Display error pop-up and redirect
        echo "<script>
                alert('Error preparing update statement: " . mysqli_error($conn) . "');
                window.location.href = '../staff-list.php';
              </script>";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $phone_number, $role, $current_image, $id);
    
    // Execute the update query and check for success
    if (mysqli_stmt_execute($stmt)) {
        // Display success pop-up and redirect
        echo "<script>
                alert('Staff details updated successfully!');
                window.location.href = '../staff-list.php';
              </script>";
    } else {
        // Display error pop-up and redirect
        echo "<script>
                alert('Error updating staff details: " . mysqli_error($conn) . "');
                window.location.href = '../staff-list.php';
              </script>";
    }

    mysqli_stmt_close($stmt);
    exit();
}
?>