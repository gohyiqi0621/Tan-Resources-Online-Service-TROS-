<?php
include 'database.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transaction_id']) && isset($_POST['status'])) {
    $transaction_id = $_POST['transaction_id'];
    $new_status = $_POST['status'];

    // Start a transaction to maintain data consistency
    $conn->begin_transaction();

    // Update status in the database
    $update_query = "UPDATE payments SET status = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ss", $new_status, $transaction_id);

    if (!$stmt->execute()) {
        $conn->rollback(); // Rollback changes if update fails
        echo "<script>
            window.location.href = '../view-appointment.php?error=" . urlencode("Failed to update status") . "';
        </script>";
        exit();
    }
    $stmt->close();

    // If status is 'Completed' or 'Canceled', remove technician availability
    if ($new_status === 'Completed' || $new_status === 'Canceled') {
        // Retrieve technician ID from payments table
        $fetch_sql = "SELECT technician_id FROM payments WHERE transaction_id = ?";
        $stmt_fetch = $conn->prepare($fetch_sql);
        $stmt_fetch->bind_param("s", $transaction_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($technician_id);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        if (!empty($technician_id)) { // Ensure technician ID is valid before deleting
            $delete_sql = "DELETE FROM technician_availability WHERE technician_id = ?";
            $stmt_delete = $conn->prepare($delete_sql);
            $stmt_delete->bind_param("i", $technician_id);
            
            if (!$stmt_delete->execute()) {
                $conn->rollback(); // Rollback changes if deletion fails
                echo "<script>
                    window.location.href = '../view-appointment.php?error=" . urlencode("Failed to remove technician availability") . "';
                </script>";
                exit();
            }
            $stmt_delete->close();
        }
    }

    // Commit the transaction if all queries succeed
    $conn->commit();
    
    // Redirect back with success message using JavaScript and ensure $new_status is correctly passed
    echo "<script>
        window.location.href = '../view-appointment.php?message=" . urlencode("Booking updated to " . $new_status) . "';
    </script>";
    exit();
} else {
    echo "<script>
        window.location.href = '../view-appointment.php?error=" . urlencode("Invalid request") . "';
    </script>";
    exit();
}
?>
