<?php
session_start();
require '../../vendor/autoload.php';
require 'database.php';

\Stripe\Stripe::setApiKey('sk_test_51QwNIpP0HU7M7BGvb7ERdCJ23L0QU28ZfaYvip1NHBa2zOvWuPdqOxtdk0fDz9FhNTxIkaKfHFaiG5P69SF0Kzwp00VAtChpvj');

// ✅ Ensure session variables are set before proceeding
$required_fields = ['total_price', 'booking_date', 'booking_time', 'technician_id', 'service_type'];
foreach ($required_fields as $field) {
    if (!isset($_SESSION[$field])) {
        die("Error: Missing required field - " . $field);
    }
}

// ✅ Debugging: Check values before passing to Stripe
error_log("Booking Time (Session): " . $_SESSION['booking_time']);

// ✅ Convert booking date and time to proper formats
$total_price = $_SESSION['total_price'] * 100; // Convert to cents
$booking_date = date("Y-m-d", strtotime($_SESSION['booking_date'])); // Ensure Y-m-d format
$booking_time = date("h:i A", strtotime($_SESSION['booking_time'])); // Convert to 12-hour format with AM/PM
$technician_id = $_SESSION['technician_id'];
$service_type = $_SESSION['service_type'];

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'myr',
                'product_data' => ['name' => 'Technician Booking'],
                'unit_amount' => $total_price,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/TROS%20NEW/customer%20page/booking/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/TROS%20NEW/customer%20page/booking/cancel.php',
        'metadata' => [
            'booking_date' => $booking_date,
            'booking_time' => $booking_time, // ✅ Store in 12-hour format with AM/PM
            'technician_id' => (string) $technician_id,
            'service_type' => $service_type,
        ],
    ]);

    header("Location: " . $checkout_session->url);
    exit();
} catch (Exception $e) {
    error_log("Stripe Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}
?>
