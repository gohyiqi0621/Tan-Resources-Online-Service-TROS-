<?php
require 'database.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = $_POST['transaction_id'] ?? null;
    $new_status = $_POST['status'] ?? null;

    if (!$transaction_id || !$new_status) {
        echo "Error: Missing transaction_id or status";
        exit();
    }

    // Start a transaction
    $conn->begin_transaction();

    // Update status in `payments` table
    $update_sql = "UPDATE payments SET status = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        echo "SQL Error: " . $conn->error;
        $conn->rollback();
        exit();
    }

    $stmt->bind_param("ss", $new_status, $transaction_id);
    if (!$stmt->execute()) {
        echo "Update Failed: " . $stmt->error;
        $conn->rollback();
        exit();
    }
    $stmt->close();

    // If status is 'Completed' or 'Canceled', remove technician availability
    if ($new_status === 'Completed' || $new_status === 'Canceled') {
        // Fetch technician_id from payments
        $fetch_sql = "SELECT technician_id FROM payments WHERE transaction_id = ?";
        $stmt_fetch = $conn->prepare($fetch_sql);
        $stmt_fetch->bind_param("s", $transaction_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($technician_id);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        if (!empty($technician_id)) {
            $delete_sql = "DELETE FROM technician_availability WHERE technician_id = ?";
            $stmt_delete = $conn->prepare($delete_sql);
            $stmt_delete->bind_param("i", $technician_id);
            if (!$stmt_delete->execute()) {
                echo "Deletion Failed: " . $stmt_delete->error;
                $conn->rollback();
                exit();
            }
            $stmt_delete->close();
        }
    }

    // Commit the transaction
    $conn->commit();
    echo "success"; // AJAX-friendly response
    $conn->close();
} else {
    echo "Invalid request";
}
?>