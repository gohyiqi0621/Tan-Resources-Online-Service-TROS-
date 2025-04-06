<?php
include 'database.php'; // Include database connection

if (isset($_POST['edit_technician'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Fetch existing image from database
    $query = "SELECT image FROM technicians WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        // Display error pop-up and redirect
        echo "<script>
                alert('Error preparing statement: " . mysqli_error($conn) . "');
                window.location.href = '../technician-list.php';
              </script>";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $technician = mysqli_fetch_assoc($result);
    $current_image = $technician['image'] ?? ''; // Handle case where no image exists
    mysqli_stmt_close($stmt);

    // Image Upload Handling
    if (!empty($_FILES['image']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (in_array($image_ext, $allowed_extensions)) {
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_new_name = uniqid() . "_" . basename($image_name);
            $target = $upload_dir . $image_new_name;

            if (move_uploaded_file($image_tmp, $target)) {
                // Delete old image if a new one is uploaded
                if (!empty($current_image) && file_exists("uploads/" . $current_image)) {
                    unlink("uploads/" . $current_image);
                }
                $current_image = $image_new_name; // Set new image name
            } else {
                // Display error pop-up for image upload failure
                echo "<script>
                        alert('Failed to upload the image. Please try again.');
                        window.location.href = '../technician-list.php';
                      </script>";
                exit();
            }
        } else {
            // Display error pop-up for invalid file type
            echo "<script>
                    alert('Invalid file type. Allowed types: jpg, jpeg, png, gif, webp.');
                    window.location.href = '../technician-list.php';
                  </script>";
            exit();
        }
    }

    // Update the technician's details
    $query = "UPDATE technicians SET name = ?, specialty = ?, location = ?, rating = ?, phone_number = ?, image = ?, price = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        // Display error pop-up and redirect
        echo "<script>
                alert('Error preparing update statement: " . mysqli_error($conn) . "');
                window.location.href = '../technician-list.php';
              </script>";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssssdi", $name, $specialty, $location, $rating, $phone_number, $current_image, $price, $id);
    
    // Execute the update query and check for success
    if (mysqli_stmt_execute($stmt)) {
        // Display success pop-up and redirect
        echo "<script>
                alert('Technician details updated successfully!');
                window.location.href = '../technician-list.php';
              </script>";
    } else {
        // Display error pop-up and redirect
        echo "<script>
                alert('Error updating technician details: " . mysqli_error($conn) . "');
                window.location.href = '../technician-list.php';
              </script>";
    }

    mysqli_stmt_close($stmt);
    exit();
}
?>