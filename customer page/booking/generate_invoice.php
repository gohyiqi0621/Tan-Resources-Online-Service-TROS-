<?php
session_start();
require '../../vendor/autoload.php'; // Autoloads Stripe and FPDF
require 'database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

\Stripe\Stripe::setApiKey('sk_test_51QwNIpP0HU7M7BGvb7ERdCJ23L0QU28ZfaYvip1NHBa2zOvWuPdqOxtdk0fDz9FhNTxIkaKfHFaiG5P69SF0Kzwp00VAtChpvj');

if (!isset($_GET['session_id'])) {
    die("Error: No session ID provided.");
}

$session_id = $_GET['session_id'];

// Extend FPDF to add a custom dashed line method
class CustomFPDF extends FPDF {
    function DashedLine($x1, $y1, $x2, $y2, $dash_length = 2, $space_length = 2) {
        $this->SetLineWidth(0.2);
        $length = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
        $dash_count = floor($length / ($dash_length + $space_length));
        $dx = ($x2 - $x1) / $dash_count;
        $dy = ($y2 - $y1) / $dash_count;

        for ($i = 0; $i < $dash_count; $i++) {
            if ($i % 2 == 0) {
                $this->Line($x1 + $i * $dx, $y1 + $i * $dy, $x1 + ($i + 1) * $dx, $y1 + ($i + 1) * $dy);
            }
        }
    }
}

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    $transaction_id = $session->payment_intent;

    $stmt = $conn->prepare("SELECT * FROM payments WHERE transaction_id = ?");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Transaction not found in database.");
    }

    $row = $result->fetch_assoc();
    $payer_email = $row['payer_email'];
    $amount_paid = $row['amount'];
    $currency = $row['currency'];
    $booking_date = $row['booking_date'];
    $booking_time = $row['booking_time'];
    $technician_id = $row['technician_id'];
    $service_type = $row['service_type'];
    $payment_time = $row['created_at'];

    // Format booking time to 12-hour format with AM/PM
    $formatted_booking_time = date("h:i A", strtotime($booking_time));

    $company_name = "Tan Resources Online Service";
    $company_address = "25, Jalan Larut, Titiwangsa Sentral";
    $company_email = "tanresourcesonlineservice@gmail.com";
    $company_phone = "+6014-2711987";
    $logo_path = "logo.png";

    $pdf = new CustomFPDF(); // Use the extended class
    $pdf->AddPage();

    // Paper-like border in deep blue
    $pdf->SetDrawColor(0, 48, 135); // #003087 for border
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(5, 5, 200, 287, 'D'); // A4 "paper" edge

    // Header: Logo and company info (larger logo)
    if (file_exists($logo_path)) {
        $logo_width = 70; // Increased from 40
        $logo_height = 50; // Increased from 30
        $x_position = (210 - $logo_width) / 2;
        $y_position = 10;
        $pdf->Image($logo_path, $x_position, $y_position, $logo_width, $logo_height);
    }

    $pdf->SetFont('Helvetica', 'B', 14);
    $pdf->SetTextColor(0, 0, 0); // Black for company name
    $pdf->Ln(55); // Adjusted spacing to account for larger logo
    $pdf->Cell(0, 8, $company_name, 0, 1, 'C');
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->SetTextColor(0, 0, 0); // Black for details
    $pdf->Cell(0, 6, $company_address, 0, 1, 'C');
    $pdf->Cell(0, 6, "T: $company_phone  |  E: $company_email", 0, 1, 'C');

    // Receipt Title with red accent divider
    $pdf->Ln(10);
    $pdf->SetFont('Helvetica', 'B', 18);
    $pdf->SetTextColor(0, 0, 0); // Black for title
    $pdf->Cell(0, 10, "OFFICIAL RECEIPT", 0, 1, 'C');
    $pdf->SetDrawColor(255, 0, 0); // #ff0000 for divider
    $pdf->SetLineWidth(0.5);
    $pdf->Line(30, $pdf->GetY(), 180, $pdf->GetY()); // Bold red divider
    $pdf->Ln(5);

    // Transaction Details
    $pdf->SetFont('Helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0); // Black for text
    $pdf->SetDrawColor(0, 48, 135); // #003087 for borders
    $pdf->SetLineWidth(0.1);
    $pdf->SetFillColor(245, 245, 245); // Off-white "paper" background

    $pdf->SetX(20);
    $pdf->Cell(80, 8, "Transaction ID:", 0);
    $pdf->Cell(90, 8, $transaction_id, 0, 1);
    $pdf->SetX(20);
    $pdf->Cell(80, 8, "Payer Email:", 0);
    $pdf->Cell(90, 8, $payer_email, 0, 1);
    $pdf->SetX(20);
    $pdf->Cell(80, 8, "Payment Date:", 0);
    $pdf->Cell(90, 8, date("F j, Y, g:i a", strtotime($payment_time)), 0, 1);

    // Service Section (boxed with red header)
    $pdf->Ln(5);
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->SetFillColor(255, 0, 0); // #ff0000 for header background
    $pdf->SetTextColor(255, 255, 255); // White text on red header
    $pdf->SetX(20);
    $pdf->Cell(170, 10, "Service Details", 1, 1, 'L', true);
    $pdf->SetFont('Helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0); // Black for details
    $pdf->SetFillColor(255, 255, 255); // White rows
    $pdf->SetX(20);
    $pdf->Cell(85, 8, "Service Type:", 1);
    $pdf->Cell(85, 8, ucwords($service_type), 1, 1);
    $pdf->SetX(20);
    $pdf->Cell(85, 8, "Technician ID:", 1);
    $pdf->Cell(85, 8, $technician_id, 1, 1);
    $pdf->SetX(20);
    $pdf->Cell(85, 8, "Booking Date:", 1);
    $pdf->Cell(85, 8, $booking_date, 1, 1);
    $pdf->SetX(20);
    $pdf->Cell(85, 8, "Booking Time:", 1);
    $pdf->Cell(85, 8, $formatted_booking_time, 1, 1); // Display with AM/PM

    // Payment Summary (boxed with blue header)
    $pdf->Ln(5);
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->SetFillColor(0, 48, 135); // #003087 for header background
    $pdf->SetTextColor(255, 255, 255); // White text on blue header
    $pdf->SetX(20);
    $pdf->Cell(170, 10, "Payment Summary", 1, 1, 'L', true);
    $pdf->SetFont('Helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0); // Black for text
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetX(20);
    $pdf->Cell(85, 8, "Amount Paid:", 1);
    $pdf->Cell(85, 8, strtoupper($currency) . " " . number_format($amount_paid, 2), 1, 1);

    // Enhanced Footer with more spacing and high-class design
    $pdf->Ln(20); // Extra space before footer
    $pdf->SetFont('Helvetica', 'I', 10);
    $pdf->SetTextColor(0, 0, 0); // Black for thank-you message
    $pdf->SetX(20);
    $pdf->Cell(0, 8, "Thank you for your business. Retain this receipt for your records.", 0, 1, 'C');
    
    // Add more space between the text and the tear-off line
    $pdf->Ln(10); // Increased spacing
    
    // Red tear-off line with a dashed effect using custom method
    $pdf->SetDrawColor(255, 0, 0); // #ff0000 for tear-off line
    $pdf->DashedLine(20, $pdf->GetY(), 190, $pdf->GetY(), 2, 2); // Custom dashed line
    
    // Add space after the tear-off line
    $pdf->Ln(8); // Space after the line
    
    // Receipt number and issue date in a more elegant format
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->SetTextColor(0, 0, 0); // Black for receipt number
    $pdf->SetX(20);
    $pdf->Cell(0, 6, "Receipt # " . $transaction_id, 0, 1, 'C');
    $pdf->SetFont('Helvetica', 'I', 8);
    $pdf->SetTextColor(0, 0, 0); // Black for issue date
    $pdf->SetX(20);
    $pdf->Cell(0, 6, "Issued on: " . date("F j, Y"), 0, 1, 'C');

    // Save and display
    $invoice_dir = "invoices/";
    if (!file_exists($invoice_dir)) {
        mkdir($invoice_dir, 0777, true);
    }
    $pdf_filename = $invoice_dir . "Receipt_" . $transaction_id . ".pdf";
    $pdf->Output('F', $pdf_filename); // Save to server
    $pdf->Output('I', "Receipt_$transaction_id.pdf"); // Display in browser
    exit;

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
}

$conn->close();
?>