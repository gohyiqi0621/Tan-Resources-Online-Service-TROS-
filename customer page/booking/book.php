<?php
// Start the session
session_start();

// Database connection
require 'database.php'; // Ensure this file connects to your MySQL database

// Function to get petrol fee based on location
function getPetrolFee($location) {
    $fees = [
        'Titiwangsa' => 5.00,
        'Setapak' => 7.00,
        'Bukit Bintang' => 10.00,
        'Kampung Baru' => 7.00,
        'Cheras' => 7.00
    ];
    return $fees[$location] ?? 20.00; // Default to 20.00 if location is not listed
}

// Check if technician_id exists in POST request and store it in session
if (isset($_POST['technician_id'])) {
    $_SESSION['technician_id'] = $_POST['technician_id']; // Save to session
}

// Retrieve technician_id from session (fallback to POST)
$technician_id = $_SESSION['technician_id'] ?? null;

// If no technician is selected, stop the script
if (!$technician_id) {
    die("Error: Technician ID is missing.");
}

// Fetch technician details from the database (excluding petrol_fee)
$sql = "SELECT * FROM technicians WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $technician_id);
$stmt->execute();
$result = $stmt->get_result();
$technician = $result->fetch_assoc();

if (!$technician) {
    die("Technician not found.");
}

// Get date and time from session (set in find_technician.php)
$booking_date = $_SESSION['booking_date'] ?? 'Not specified';
$booking_time = $_SESSION['booking_time'] ?? 'Not specified';

// Convert booking_time to 12-hour format with AM/PM if specified
$formatted_time = 'Not specified';
if ($booking_time !== 'Not specified') {
    $time_obj = DateTime::createFromFormat('H:i:s', $booking_time);
    if ($time_obj) {
        $formatted_time = $time_obj->format('h:i A'); // e.g., "12:03 PM"
    }
}

// Get petrol fee dynamically based on technician's location
$petrol_fee = getPetrolFee($technician['location']);

// Calculate total price
$total_price = $technician['price'] + $petrol_fee;

// Format the prices in MYR
$service_price_value = number_format($technician['price'], 2);
$petrol_fee_value = number_format($petrol_fee, 2);
$total_price_value = number_format($total_price, 2);

// Store necessary data in session for payment processing
$_SESSION['total_price'] = $total_price;
$_SESSION['technician_id'] = $technician_id;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* More beautiful, aesthetic, and elegant redesign for a technical booking service */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #e6e9f0, #f4f7fc);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 700px;
            width: 100%;
            padding: 50px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 50, 0.1);
            text-align: center;
            animation: fadeIn 1.2s ease-in-out;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(15px); /* Enhanced glassmorphism effect */
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.4), transparent);
            opacity: 0.6;
            z-index: -1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: #003087;
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 35px;
            text-transform: none;
            letter-spacing: 1.5px;
            position: relative;
            padding-bottom: 12px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #003087);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 35px;
            text-decoration: none;
            color: #003087;
            font-weight: 600;
            font-size: 16px;
            transition: color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
            text-align: left;
            width: 100%;
        }

        .back-link i {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .back-link:hover {
            color: #60a5fa;
            transform: translateX(-5px);
        }

        .back-link:hover i {
            transform: translateX(-3px);
        }

        /* Technician Card */
        .technician-card {
            display: flex;
            align-items: center;
            gap: 35px;
            padding: 30px;
            background: linear-gradient(135deg, #ffffff, #e6f0ff);
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0, 0, 50, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 35px;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            position: relative;
            z-index: 1;
        }

        .technician-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 18px 45px rgba(0, 0, 50, 0.12);
        }

        .technician-card img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #003087;
            box-shadow: 0 5px 20px rgba(0, 48, 135, 0.2);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .technician-card img:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0, 48, 135, 0.3);
        }

        .technician-info {
            flex: 1;
            text-align: left;
        }

        .technician-info h3 {
            margin: 0 0 12px;
            color: #003087;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: 1px;
        }

        .technician-info p {
            margin: 12px 0;
            color: #64748b;
            font-size: 16px;
            font-weight: 500;
            line-height: 1.6;
            display: flex;
            align-items: center;
        }

        .technician-info i {
            margin-right: 12px;
            color: #003087;
            font-size: 18px;
            transition: transform 0.4s ease;
        }

        .technician-card:hover .technician-info i {
            transform: translateX(5px);
        }

        .rating {
            color: #003087;
            font-size: 16px;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Booking and Price Details */
        .booking-details, .price-details {
            margin-top: 35px;
            padding: 30px;
            border-radius: 14px;
            background: linear-gradient(135deg, #ffffff, #e6f0ff);
            box-shadow: 0 10px 30px rgba(0, 0, 50, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: left;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .booking-details:hover, .price-details:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 50, 0.1);
        }

        .booking-details p, .price-details p {
            margin: 12px 0;
            font-size: 16px;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-details .label, .price-details .label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #003087;
            width: 150px; /* Fixed width for labels */
        }

        .booking-details i, .price-details i {
            margin-right: 12px;
            color: #003087;
            font-size: 18px;
        }

        .booking-details span, .price-details span {
            font-weight: 600;
            color: #003087;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        /* Align MYR and numeric values */
        .price-details .currency {
            display: inline-block;
            width: 40px; /* Fixed width for MYR to align all "M"s */
            text-align: left;
        }

        .price-details .value {
            display: inline-block;
            text-align: right;
            min-width: 60px; /* Ensure numbers are aligned */
        }

        /* Price Disclaimer Paragraph */
        .price-disclaimer {
            margin-top: 25px;
            padding: 15px 20px;
            background: linear-gradient(135deg, #e6f0ff, #ffffff);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 50, 0.04);
            color: #94a3b8;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .price-disclaimer:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 50, 0.08);
        }

        /* Enhanced Pay with Stripe Button */
        .book-button {
            display: block;
            width: 100%;
            padding: 18px;
            margin-top: 40px;
            text-align: center;
            background: linear-gradient(45deg, #003087, #1e40af);
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.5s ease, background 0.5s ease, box-shadow 0.5s ease;
            box-shadow: 0 10px 30px rgba(0, 48, 135, 0.3);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .book-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.6s;
            z-index: -1;
        }

        .book-button::after {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            width: 50px;
            height: 50px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent);
            z-index: -1;
            opacity: 0.4;
        }

        .book-button:hover::before {
            left: 100%;
        }

        .book-button:hover {
            background: linear-gradient(45deg, #1e40af, #003087);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 48, 135, 0.5);
        }

        .book-button:active {
            transform: translateY(0);
            box-shadow: 0 5px 15px rgba(0, 48, 135, 0.2);
        }

        /* Specific adjustments for the Stripe button */
        .stripe-button {
            width: 100%;
            margin: 40px auto 0;
            padding: 18px;
            font-size: 18px;
        }

        .phone-link {
            text-decoration: none;
            color: #003087; /* Updated to dark blue */
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .phone-link:hover {
            color: #1e40af; /* Slightly lighter blue on hover for contrast */
        }

        /* Icon Adjustments */
        .technician-info .fa-map-marker-alt {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="booknow.html" class="back-link" onclick="return confirmNavigation(event)"><i class="fas fa-arrow-left"></i> Back to Search</a>

        <h2>Technician Booking</h2>

        <!-- Technician Info -->
        <div class="technician-card">
            <img src="../../staff&adminPage/backend/uploads/<?php echo htmlspecialchars($technician['image']); ?>" alt="Technician Photo">
            <div class="technician-info">
                <h3><?php echo htmlspecialchars($technician['name']); ?></h3>
                <p><i class="fas fa-screwdriver-wrench"></i> <?php echo htmlspecialchars($technician['specialty']); ?></p>
                <p>
                    <i class="fas fa-phone"></i>
                    <a href="tel:<?php echo htmlspecialchars($technician['phone_number']); ?>" class="phone-link">
                        <?php echo htmlspecialchars($technician['phone_number']); ?>
                    </a>
                </p>
                <p><i class="fas fa-map-pin"></i> <?php echo htmlspecialchars($technician['location']); ?></p>
                <p class="rating">
                    <?php for ($i = 0; $i < floor($technician['rating']); $i++) echo '<i class="fas fa-star"></i>'; ?>
                    <?php if ($technician['rating'] - floor($technician['rating']) >= 0.5) echo '<i class="fas fa-star-half-alt"></i>'; ?>
                </p>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <p><span class="label"><i class="fas fa-calendar-alt"></i> Date:</span> <span><?php echo $booking_date; ?></span></p>
            <p><span class="label"><i class="fas fa-clock"></i> Time:</span> <span><?php echo $formatted_time; ?></span></p>
        </div>

        <!-- Price Details -->
        <div class="price-details">
            <p>
                <span class="label"><i class="fas fa-money-bill-wave"></i> Service Price:</span>
                <span><span class="currency">MYR</span> <span class="value"><?php echo $service_price_value; ?></span></span>
            </p>
            <p>
                <span class="label"><i class="fas fa-gas-pump"></i> Petrol Fee:</span>
                <span><span class="currency">MYR</span> <span class="value"><?php echo $petrol_fee_value; ?></span></span>
            </p>
            <p>
                <span class="label"><i class="fas fa-wallet"></i> Total:</span>
                <span><span class="currency">MYR</span> <span class="value"><?php echo $total_price_value; ?></span></span>
            </p>
        </div>

        <!-- Price Disclaimer Paragraph -->
        <p class="price-disclaimer">This is only for basic service price and petrol price. Hardware have an extra charge, which will be charged after service.</p>

        <form action="checkout.php" method="POST">
            <button type="submit" class="book-button stripe-button">Pay with Stripe</button>
        </form>
    </div>

</body>
</html>