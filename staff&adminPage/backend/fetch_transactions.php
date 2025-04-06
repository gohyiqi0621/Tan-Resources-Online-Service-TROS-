<?php
require 'database.php'; // Ensure the database connection is included

$sql = "SELECT id, transaction_id, payer_email, amount, currency, booking_date, booking_time, service_type, status, technician_id 
        FROM payments 
        ORDER BY booking_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Fetch technician name
        $technician_name = "Not Assigned";
        if (!empty($row['technician_id'])) {
            $tech_query = "SELECT name FROM technicians WHERE id = ?";
            $stmt = $conn->prepare($tech_query);
            $stmt->bind_param("i", $row['technician_id']);
            $stmt->execute();
            $stmt->bind_result($tech_name);
            if ($stmt->fetch()) {
                $technician_name = $tech_name;
            }
            $stmt->close();
        }

        // Ensure status class formatting
        $statusClass = "status-" . strtolower(str_replace(" ", "-", $row['status']));

        // Status action with modal (only for Pending), centered button
        $actionContent = "";
        if (trim($row['status']) === 'Pending') {
            $actionContent = "
                <div class='actions' style='text-align: center;'>
                    <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#statusModal-{$row['transaction_id']}'>
                        Update
                    </button>
                </div>

                <!-- Modal styled like sales-report.php -->
                <div class='modal fade' id='statusModal-{$row['transaction_id']}' tabindex='-1' aria-labelledby='statusModalLabel-{$row['transaction_id']}' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content' style='background: #1e1e2d; border: none; border-radius: 12px; color: #ffffff;'>
                            <div class='modal-header' style='background: #2a2a3c; border-bottom: 2px solid #3d3d5c;'>
                                <h5 class='modal-title' id='statusModalLabel-{$row['transaction_id']}' style='color: #ffffff;'>Update Status</h5>
                                <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form method='POST' action='backend/update_status.php'>
                                <div class='modal-body' style='padding: 20px;'>
                                    <p style='margin-bottom: 15px;'>Transaction ID: <strong>{$row['transaction_id']}</strong></p>
                                    <input type='hidden' name='transaction_id' value='{$row['transaction_id']}'>
                                    <div class='mb-3'>
                                        <label for='status-{$row['transaction_id']}' class='form-label' style='color: #ffffff; font-weight: 600;'>New Status</label>
                                        <select name='status' id='status-{$row['transaction_id']}' class='form-select form-select-sm' style='background: #2d3748; color: #ffffff; border: none; padding: 12px; border-radius: 8px;' required>
                                            <option value='' disabled selected>Select Status</option>
                                            <option value='Completed'>Mark as Completed</option>
                                            <option value='Canceled'>Cancel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='modal-footer' style='border-top: 2px solid #3d3d5c;'>
                                    <button type='button' class='btn btn-secondary btn-sm' style='background: #667eea; color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px;' data-bs-dismiss='modal'>Close</button>
                                    <button type='submit' class='btn btn-primary btn-sm' style='background: linear-gradient(135deg, #667eea, #764ba2); color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px;'>Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
        } else {
            $actionContent = "<div class='actions' style='text-align: center; color: #ffffff;'>No action is needed</div>";
        }

        // Output table row
        echo "<tr>
                <td>{$row['transaction_id']}</td>
                <td>{$technician_name}</td>
                <td>{$row['payer_email']}</td>
                <td>{$row['booking_date']}</td>
                <td>{$row['booking_time']}</td>
                <td>{$row['service_type']}</td>
                <td class='{$statusClass}'>{$row['status']}</td>
                <td>{$actionContent}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8' class='text-center'>No transactions found</td></tr>";
}

$conn->close();
?>