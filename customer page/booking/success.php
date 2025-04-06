<?php
session_start();
require '../../vendor/autoload.php';
require 'database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

\Stripe\Stripe::setApiKey('sk_test_51QwNIpP0HU7M7BGvb7ERdCJ23L0QU28ZfaYvip1NHBa2zOvWuPdqOxtdk0fDz9FhNTxIkaKfHFaiG5P69SF0Kzwp00VAtChpvj');

// Check if session_id is provided
if (!isset($_GET['session_id'])) {
    die("Error: No session ID provided.");
}

$session_id = $_GET['session_id'];

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    $customer_email = $session->customer_details->email;
    $transaction_id = $session->payment_intent;
    $amount = $session->amount_total / 100; // Convert cents to MYR
    $currency = strtoupper($session->currency);
    $payment_status = $session->payment_status;

    $booking_date = $session->metadata->booking_date ?? null;
    $booking_time = $session->metadata->booking_time ?? null;
    $technician_id = $session->metadata->technician_id ?? null;
    $service_type = $session->metadata->service_type ?? null;

    if (!$booking_date || !$booking_time || !$service_type) {
        die("Error: Booking details are missing in Stripe metadata.");
    }

    if ($technician_id === null) {
        die("Error: technician_id is missing in Stripe metadata.");
    }

    if (!isset($_SESSION['user_id'])) {
        die("Error: User ID is missing in session.");
    }
    $user_id = $_SESSION['user_id'];

    // Insert into payments table
    $stmt = $conn->prepare("INSERT INTO payments (user_id, transaction_id, payer_email, amount, currency, payment_status, booking_date, booking_time, service_type, 
    technician_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdsssssi", $user_id, $transaction_id, $customer_email, $amount, $currency, $payment_status, $booking_date, $booking_time, $service_type, 
    $technician_id);

    if (!$stmt->execute()) {
        die("Database Insert Error: " . $stmt->error);
    }
    $stmt->close();

    // Validate technician_id exists in the technicians table
    $check_technician_stmt = $conn->prepare("SELECT id FROM technicians WHERE id = ?");
    $check_technician_stmt->bind_param("i", $technician_id);
    $check_technician_stmt->execute();
    $check_technician_result = $check_technician_stmt->get_result();

    if ($check_technician_result->num_rows === 0) {
        die("Error: Invalid technician_id. Technician does not exist in the database.");
    }
    $check_technician_stmt->close();

    // Insert into technician_availability table
    $insert_availability_stmt = $conn->prepare("INSERT INTO technician_availability (technician_id, booking_date) VALUES (?, ?)");
    $insert_availability_stmt->bind_param("is", $technician_id, $booking_date);

    if (!$insert_availability_stmt->execute()) {
        die("Database Insert Error: Failed to update technician availability - " . $insert_availability_stmt->error);
    }
    $insert_availability_stmt->close();

    $invoice_path = "invoices/Invoice_" . $transaction_id . ".pdf";

    $update_stmt = $conn->prepare("UPDATE payments SET invoice_path = ? WHERE transaction_id = ?");
    $update_stmt->bind_param("ss", $invoice_path, $transaction_id);

    if (!$update_stmt->execute()) {
        die("Database Update Error: " . $update_stmt->error);
    }
    $update_stmt->close();

    $invoice_url = "http://localhost/TROS%20NEW/customer%20page/booking/generate_invoice.php?session_id=" . urlencode($session_id);
    file_get_contents($invoice_url);

} catch (Exception $e) {
    error_log("Stripe Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - TROS</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css for Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #e6f0fa 0%, #d6e3f8 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
        }
        .receipt-card {
            background: #fff url('https://www.transparenttextures.com/patterns/paper-fibers.png');
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 48, 135, 0.15), 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.8s ease;
            border: 2px dashed #e0e0e0; /* Perforated edge effect */
            background-clip: padding-box;
            position: relative;
        }
        .receipt-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 20px;
            height: 20px;
            background: #fff;
            border-bottom-left-radius: 20px;
            box-shadow: -5px 5px 10px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding-top: 5px;
        }
        .receipt-header .logo {
            font-size: 2.8rem;
            font-weight: 700;
            color: #003087;
            display: block; /* First row */
            margin-bottom: 10px;
        }
        .receipt-header .logo i {
            color: #ff0000;
            margin-right: 10px;
            font-size: 2.2rem;
        }
        .receipt-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #003087;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .receipt-header .check-icon {
            color: #28a745;
            font-size: 1.5rem;
            background: rgba(40, 167, 69, 0.1);
            padding: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        .receipt-header .date-time {
            font-size: 0.9rem;
            color: #666;
            font-weight: 300;
            margin-top: 10px;
        }
        .receipt-details {
            border-top: 2px dashed #e0e0e0;
            padding-top: 30px;
            margin-bottom: 60px;
            position: relative;
        }
        .receipt-details .row {
            margin-bottom: 20px;
            font-size: 1rem;
            color: #333;
            opacity: 0;
            animation: printRow 0.5s ease forwards;
            position: relative;
        }
        .receipt-details .row:nth-child(1) { animation-delay: 0.5s; }
        .receipt-details .row:nth-child(2) { animation-delay: 1.0s; }
        .receipt-details .row:nth-child(3) { animation-delay: 1.5s; }
        .receipt-details .row:nth-child(4) { animation-delay: 2.0s; }
        .receipt-details .row:nth-child(5) { animation-delay: 2.5s; }
        .receipt-details .row:nth-child(6) { animation-delay: 3.0s; }
        .receipt-details .label {
            color: #003087;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .receipt-details .label i {
            margin-right: 10px;
            color: #ff0000;
            font-size: 1.2rem;
            opacity: 0.8;
        }
        .receipt-details .value {
            color: #444;
            font-weight: 400;
        }
        .receipt-details .highlight {
            color: #28a745;
            font-weight: 500;
        }
        .stamp {
            position: absolute;
            top: 120px; /* Lowered to span Amount Paid, Payment Status, and Service Type */
            right: 20px;
            width: 90px; /* Slightly larger for impact */
            height: 90px;
            background: radial-gradient(circle, rgba(255, 0, 0, 0.1), transparent);
            border: 3px solid #ff0000; /* Thicker border */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transform: rotate(15deg);
            animation: stampGlow 2s infinite, stampAction 1s ease forwards;
            animation-delay: 2.7s; /* After Service Type row */
            opacity: 0;
            box-shadow: 0 0 5px rgba(255, 0, 0, 0.3); /* Slight shadow for depth */
            background-image: url('https://www.transparenttextures.com/patterns/grunge-wall.png'); /* Grunge texture for realism */
            background-blend-mode: overlay;
        }
        .stamp::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: radial-gradient(circle, transparent, rgba(255, 0, 0, 0.1)); /* Ink smudge effect */
            z-index: -1;
        }
        .stamp .stamp-text {
            font-size: 0.9rem;
            font-weight: 800;
            color: #ff0000;
            text-align: center;
            text-transform: uppercase;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Text shadow for depth */
        }
        .receipt-footer {
            border-top: 2px dashed #e0e0e0;
            padding-top: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .btn-custom {
            background: #003087;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            color: #fff;
            transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 48, 135, 0.2);
        }
        .btn-custom:hover {
            background: #ff0000;
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(255, 0, 0, 0.3);
        }
        .btn-receipt {
            background: #003087 url('https://www.transparenttextures.com/patterns/paper-fibers.png');
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-receipt i {
            font-size: 1.2rem;
        }
        .btn-receipt:hover {
            background: #ff0000 url('https://www.transparenttextures.com/patterns/paper-fibers.png');
        }
        .btn-return {
            background: #ff0000;
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.2);
        }
        .btn-return:hover {
            background: #cc0000;
            box-shadow: 0 7px 20px rgba(204, 0, 0, 0.3);
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-weight: 300;
        }
        .footer-text a {
            color: #ff0000;
            text-decoration: none;
            font-weight: 400;
        }
        .footer-text a:hover {
            text-decoration: underline;
            color: #cc0000;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 4rem;
            color: rgba(0, 48, 135, 0.03);
            pointer-events: none;
            font-weight: 700;
            text-transform: uppercase;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes printRow {
            0% { opacity: 0; transform: translateY(-10px); }
            50% { opacity: 0.5; transform: translateY(0); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        @keyframes stampGlow {
            0% { box-shadow: 0 0 5px rgba(255, 0, 0, 0.3); }
            50% { box-shadow: 0 0 15px rgba(255, 0, 0, 0.5); }
            100% { box-shadow: 0 0 5px rgba(255, 0, 0, 0.3); }
        }
        @keyframes stampAction {
            0% { opacity: 0; transform: rotate(15deg) translateY(-80px) scale(1.3); }
            40% { opacity: 0.7; transform: rotate(15deg) translateY(15px) scale(0.9); }
            60% { opacity: 0.9; transform: rotate(15deg) translateY(-10px) scale(1.1); }
            80% { opacity: 1; transform: rotate(15deg) translateY(5px) scale(0.95); }
            100% { opacity: 1; transform: rotate(15deg) translateY(0) scale(1); }
        }
        @media (max-width: 576px) {
            .receipt-card {
                padding: 20px;
            }
            .receipt-header .logo {
                font-size: 2rem;
            }
            .receipt-header h2 {
                font-size: 1.5rem;
            }
            .btn-custom {
                width: 100%;
                margin: 10px 0;
            }
            .receipt-footer {
                flex-direction: column;
                gap: 10px;
            }
            .stamp {
                width: 70px;
                height: 70px;
                top: 60px; /* Adjusted for smaller screens */
                right: 10px;
            }
            .stamp .stamp-text {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="receipt-card animate__animated animate__fadeIn">
            <div class="watermark">TROS</div>
            <div class="receipt-header">
                <div class="logo"><i class="fas fa-tools"></i> TROS</div>
                <h2><i class="fas fa-check-circle check-icon"></i> Payment Successful</h2>
                <div class="date-time">
                    Issued on: <?php echo date('Y-m-d H:i:s'); ?>
                </div>
            </div>
            <div class="receipt-details">
                <div class="row">
                    <div class="col-5 label"><i class="fas fa-receipt"></i> Transaction ID:</div>
                    <div class="col-7 value"><?php echo htmlspecialchars($transaction_id); ?></div>
                </div>
                <div class="row">
                    <div class="col-5 label"><i class="fas fa-envelope"></i> Payer Email:</div>
                    <div class="col-7 value"><?php echo htmlspecialchars($customer_email); ?></div>
                </div>
                <div class="row">
                    <div class="col-5 label"><i class="fas fa-money-bill-wave"></i> Amount Paid:</div>
                    <div class="col-7 value highlight">MYR <?php echo htmlspecialchars($amount); ?></div>
                </div>
                <div class="row">
                    <div class="col-5 label"><i class="fas fa-check"></i> Payment Status:</div>
                    <div class="col-7 value highlight"><?php echo htmlspecialchars($payment_status); ?></div>
                </div>
                <div class="row">
                    <div class="col-5 label"><i class="fas fa-tools"></i> Service Type:</div>
                    <div class="col-7 value"><?php echo htmlspecialchars($service_type); ?></div>
                </div>
                <div class="row">
                    <div class="col-5 label"><i class="fas fa-calendar-alt"></i> Booking Scheduled:</div>
                    <div class="col-7 value"><?php echo htmlspecialchars($booking_date . " at " . $booking_time); ?></div>
                </div>
                <div class="stamp">
                    <div class="stamp-text">TROS<br>Official</div>
                </div>
            </div>
            <div class="receipt-footer">
                <a href="generate_invoice.php?session_id=<?php echo urlencode($session_id); ?>" class="btn btn-custom btn-receipt"><i class="fas fa-file-pdf"></i> View Receipt (PDF)</a>
                <a href="../index.html" class="btn btn-custom btn-return">Return to Main Page</a>
            </div>
        </div>
        <div class="footer-text">
            Need help? <a href="/contact">Contact Us</a> or call <a href="tel:+6014-271987">+6014-271987</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>