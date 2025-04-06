<?php
session_start();
require 'booking/database.php'; // ✅ Database connection

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $errorMessage = "Access Denied: You must log in to view your bookings.";
} else {
    $user_id = $_SESSION['user_id']; // ✅ Get logged-in user's ID

    // ✅ Fetch bookings for the logged-in user with technician details
    $sql = "SELECT 
                payments.transaction_id, 
                payments.amount, 
                payments.currency, 
                payments.payment_method, 
                payments.payment_status, 
                payments.booking_date, 
                payments.booking_time, 
                payments.service_type, 
                payments.status, 
                technicians.name AS technician_name 
            FROM payments 
            LEFT JOIN technicians ON payments.technician_id = technicians.id
            WHERE payments.user_id = ? 
            ORDER BY payments.booking_date DESC, payments.booking_time DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $errorMessage = "SQL Error: " . $conn->error;
    } else {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        $stmt->close();
    }
    $conn->close();
}
?>


<!DOCTYPE HTML>
<html lang="en-US">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Tan Resources Online Service</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Favicon -->
	<link rel="icon" type="image/png" sizes="56x56" href="assets/images/fav-icon/icon.png">
	<!-- bootstrap CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" media="all">
	<!-- carousel CSS -->
	<link rel="stylesheet" href="assets/css/owl.carousel.min.css" type="text/css" media="all">
	<!-- animate CSS -->
	<link rel="stylesheet" href="assets/css/animate.css" type="text/css" media="all">
	<!-- animated-text CSS -->
	<link rel="stylesheet" href="assets/css/animated-text.css" type="text/css" media="all">
	<!-- font-awesome CSS -->
	<link rel="stylesheet" href="assets/css/all.min.css" type="text/css" media="all">
	<!-- font-flaticon CSS -->
	<link rel="stylesheet" href="assets/css/flaticon.css" type="text/css" media="all">
	<!-- theme-default CSS -->
	<link rel="stylesheet" href="assets/css/theme-default.css" type="text/css" media="all">
	<!-- meanmenu CSS -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css" type="text/css" media="all">
	<!-- transitions CSS -->
	<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css" media="all">
	<!-- venobox CSS -->
	<link rel="stylesheet" href="venobox/venobox.css" type="text/css" media="all">
	<!-- bootstrap icons -->
	<link rel="stylesheet" href="assets/css/bootstrap-icons.css" type="text/css" media="all">
	<!-- Main Style CSS -->
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="all">  
	<!-- responsive CSS -->
	<link rel="stylesheet" href="assets/css/responsive.css" type="text/css" media="all">
	<!--boxicons CSS-->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<!-- modernizr js -->
	<script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>

	<!--boxicons CSS-->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<!--font awesome-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

	<style>

		/* Page Title for Booking History */
		.booking-history-title {
			text-align: center;
			color: #062452;
		}

		/* Error Messages */
		.error, .no-bookings {
			text-align: center;
			font-size: 18px;
			font-weight: bold;
			padding: 10px;
			color: #da242b;
		}

		/* Table Styling */
		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
			background: #ffffff;
			box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
			overflow: hidden;
			margin-bottom: 100px;
		}

		/* Table Header */
		th {
			background: #062452;
			color: white;
			padding: 12px;
			text-align: left;
		}

		/* Table Rows */
		td {
			padding: 12px;
		}

		/* Alternate Row Colors */
		tr:nth-child(even) {
			background: #f9f9f9;
		}

		/* Decrease space for the Technician Name column */
		th:nth-child(2), td:nth-child(2) {
			width: 10px; /* Adjust as needed */
			overflow: hidden;
			text-overflow: ellipsis; /* Adds "..." if the text is too long */
		}


		/* Increase space for Action column */
		th:last-child, td:last-child {
			width: 150px; /* Adjust width as needed */
			text-align: center;
		}


		/* Status Colors */
		.status-canceled{
			color: #da242b; /* Bold Red */
			font-weight: bold;
		}

		.status-completed {
			color: #28a745; /* Bold Green */
			font-weight: bold;
		}

		
		.status-pending {
			color: #ff9900; /* Orange for Canceled */
			font-weight: bold;
		}

		/* Responsive Table */
		@media screen and (max-width: 768px) {
			table {
				width: 100%;
				display: block;
				overflow-x: auto;
				white-space: nowrap;
			}
		}

		/* Dropdown styling */
		.status-dropdown {
			padding: 5px;
			font-size: 14px;
			border-radius: 5px;
			border: 1px solid #ccc;
		}

		/* Update Button */
		.update-status {
			background-color: #062452; /* Dark Blue (Matches Table Header & Update Button) */
			color: white;
			padding: 6px 20px;
			font-size: 14px;
			border: none;
			cursor: pointer;
			border-radius: 5px;
			font-weight: bold;
			transition: background 0.3s ease-in-out, transform 0.2s;
		}

		.update-status:hover {
			background-color: #da242b; /* Red to match hover effect */
			transform: scale(1.05);
		}

		/* Feedback Button */
		.feedback-btn {
			background-color: #062452; /* Dark Blue (Matches Table Header & Update Button) */
			color: white;
			padding: 6px 12px;
			font-size: 14px;
			border: none;
			cursor: pointer;
			border-radius: 5px;
			font-weight: bold;
			transition: background 0.3s ease-in-out, transform 0.2s;
		}

		/* Hover Effect */
		.feedback-btn:hover {
			background-color: #da242b; /* Red (Matches Hover Effect of Update Button) */
			transform: scale(1.05);
		}

		/* Feedback Modal Background */
		.modal {
			display: none; /* Hidden by default */
			position: fixed;
			z-index: 1000;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5); /* Dark overlay */
			justify-content: center;
			align-items: center;
		}

		/* Modal Content */
		.modal-content {
			background-color: white;
			padding: 20px;
			border-radius: 10px;
			width: 40%;
			text-align: center;
			position: relative;
			box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
		}

		/* Close Button */
		.close-btn {
			position: absolute;
			top: 10px;
			right: 15px;
			font-size: 24px;
			cursor: pointer;
		}

		/* Feedback Textarea */
		#feedbackText {
			width: 90%;
			padding: 10px;
			margin-top: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			font-size: 14px;
		}

		/* Submit Button */
		.submit-feedback-btn {
			background-color: #062452; /* Dark Blue */
			color: white;
			padding: 8px 16px;
			border: none;
			cursor: pointer;
			border-radius: 5px;
			transition: background 0.3s ease-in-out;
		}

		.submit-feedback-btn:hover {
			background-color: #da242b; /* Red on Hover */
		}

		/* Feedback Modal Background */
		.modal {
			display: none;
			position: fixed;
			z-index: 1000;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.6); /* Slightly darker overlay */
			justify-content: center;
			align-items: center;
			animation: fadeIn 0.3s ease-in-out;
		}

		/* Modal Content */
		.modal-content {
			background-color: #ffffff;
			padding: 30px;
			border-radius: 15px;
			width: 90%;
			max-width: 500px; /* Slightly wider modal */
			text-align: center;
			position: relative;
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
			animation: slideIn 0.3s ease-in-out;
		}

		/* Modal Title */
		.modal-title {
			font-size: 28px;
			color: #062452; /* Dark Blue */
			margin-bottom: 20px;
			font-weight: 700;
		}

		/* Close Button */
		.close-btn {
			position: absolute;
			top: 15px;
			right: 20px;
			font-size: 28px;
			color: #888;
			cursor: pointer;
			transition: color 0.2s ease;
		}

		.close-btn:hover {
			color: #da242b; /* Red on hover */
		}

		/* Star Rating Section */
		.star-rating-section {
			margin: 20px 0;
			text-align: center;
		}

		.rating-label {
			display: block;
			font-size: 16px;
			color: #333;
			margin-bottom: 10px;
			font-weight: 500;
		}

		.stars {
			display: inline-block;
		}

		.star {
			font-size: 32px; /* Slightly larger stars */
			color: #ddd; /* Light grey for unselected stars */
			cursor: pointer;
			transition: color 0.2s ease, transform 0.2s ease;
			margin: 0 3px;
		}

		.star:hover,
		.star.selected {
			color: #ffcc00; /* Bright gold for selected/hovered stars */
			transform: scale(1.2); /* Slight scale-up on hover/select */
		}

		/* Feedback Section */
		.feedback-section {
			margin: 20px 0;
			text-align: left;
		}

		.feedback-label {
			display: block;
			font-size: 16px;
			color: #333;
			margin-bottom: 10px;
			font-weight: 500;
		}

		/* Feedback Textarea */
		#feedbackText {
			width: 100%;
			padding: 12px;
			border: 2px solid #e0e0e0;
			border-radius: 8px;
			font-size: 14px;
			resize: none; /* Prevent resizing */
			transition: border-color 0.3s ease;
		}

		#feedbackText:focus {
			border-color: #062452; /* Dark Blue on focus */
			outline: none;
			box-shadow: 0 0 5px rgba(6, 36, 82, 0.2);
		}

		/* Submit Button */
		.submit-feedback-btn {
			background-color: #062452; /* Dark Blue */
			color: white;
			padding: 12px 30px;
			border: none;
			border-radius: 8px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: background-color 0.3s ease, transform 0.2s ease;
		}

		.submit-feedback-btn:hover {
			background-color: #da242b; /* Red on hover */
			transform: translateY(-2px); /* Slight lift effect */
		}

		/* Animations */
		@keyframes fadeIn {
			from {
				opacity: 0;
			}
			to {
				opacity: 1;
			}
		}

		@keyframes slideIn {
			from {
				transform: translateY(-20px);
				opacity: 0;
			}
			to {
				transform: translateY(0);
				opacity: 1;
			}
		}

		/* Image Upload Section */
		.image-upload-section {
			margin: 20px 0;
			text-align: left;
		}

		.image-upload-label {
			display: block;
			font-size: 16px;
			color: #333;
			margin-bottom: 10px;
			font-weight: 500;
		}

		#feedbackImage {
			width: 100%;
			padding: 10px;
			border: 2px dashed #e0e0e0;
			border-radius: 8px;
			font-size: 14px;
			cursor: pointer;
			transition: border-color 0.3s ease;
		}

		#feedbackImage:hover {
			border-color: #062452; /* Dark Blue on hover */
		}

		#feedbackImage:focus {
			border-color: #062452;
			outline: none;
		}

		/* Image Preview */
		.image-preview {
			margin-top: 10px;
			text-align: center;
		}

		.image-preview img {
			max-width: 100%;
			max-height: 150px;
			border-radius: 8px;
			border: 1px solid #e0e0e0;
			display: none; /* Hidden by default */
		}

		/* Modal Background */
		.modal {
			display: none; /* Hidden by default */
			position: fixed;
			z-index: 1000;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.6);
			justify-content: center;
			align-items: center;
			animation: fadeIn 0.3s ease-in-out;
		}

		/* Modal Content */
		.modal-content {
			background-color: #ffffff;
			padding: 30px;
			border-radius: 15px;
			width: 90%;
			max-width: 450px;
			text-align: center;
			position: relative;
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
			animation: slideIn 0.3s ease-in-out;
		}

		/* Modal Title */
		.modal-title {
			font-size: 24px;
			color: #062452;
			font-weight: 700;
			margin-bottom: 10px;
		}

		/* Modal Subtitle */
		.modal-subtitle {
			font-size: 16px;
			color: #555;
			margin-bottom: 15px;
		}

		/* Close Button */
		.close-btn {
			position: absolute;
			top: 15px;
			right: 20px;
			font-size: 24px;
			color: #888;
			cursor: pointer;
			transition: color 0.2s ease;
		}

		.close-btn:hover {
			color: #da242b;
		}

		/* Status Buttons */
		.status-options {
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.status-btn {
			width: 100%;
			padding: 12px;
			font-size: 16px;
			font-weight: bold;
			border: none;
			cursor: pointer;
			border-radius: 8px;
			transition: background 0.3s ease, transform 0.2s ease;
		}

		/* Completed Button */
		.status-btn.completed {
			background-color: #28a745;
			color: white;
		}

		.status-btn.completed:hover {
			background-color: #218838;
			transform: scale(1.05);
		}

		/* Canceled Button */
		.status-btn.canceled {
			background-color: #da242b;
			color: white;
		}

		.status-btn.canceled:hover {
			background-color: #c82333;
			transform: scale(1.05);
		}

		/* Cancel Button */
		.cancel-btn {
			background-color: #ccc;
			color: #333;
			padding: 10px;
			margin-top: 15px;
			border: none;
			width: 100%;
			font-size: 14px;
			font-weight: bold;
			border-radius: 8px;
			cursor: pointer;
			transition: background 0.3s ease, transform 0.2s ease;
		}

		.cancel-btn:hover {
			background-color: #bbb;
			transform: scale(1.05);
		}

		/* Animations */
		@keyframes fadeIn {
			from { opacity: 0; }
			to { opacity: 1; }
		}

		@keyframes slideIn {
			from { transform: translateY(-20px); opacity: 0; }
			to { transform: translateY(0); opacity: 1; }
		}

	</style>
</head>



<body>



	<!-- loder -->
	<div class="loader-wrapper">
		<div class="loader"></div>
		<div class="loder-section left-section"></div>
		<div class="loder-section right-section"></div>
	</div>



	

	<!--==================================================-->
	<!-- Start hendrio Top Menu section -->
	<!--==================================================-->
	<div class="header-top-section">
		<div class="container">
			<div class="row align-items-center d-flex">
				<div class="col-lg-6">
					<div class="header-address-info">
						<p> <i class="bi bi-geo-alt"></i> 25, Jalan Larut, Titiwangsa Sentral <span> <i class="bi bi-envelope-open"></i> tanresourcesonlineservice@gmail.com </span></p>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="header-top-right text-right">
						<div class="hendrio-social-icon">
						<ul>
							<li><a href="https://www.facebook.com/people/Tan-resources/100065744582741/"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="https://wa.me/60142711987"><i class="fab fa-whatsapp"></i></a></li>
						</ul>
					</div>
					<div class="phone-number">
						<p> <i class="fas fa-phone-square-alt"></i> <span>Call Us :</span> +6014-2711987 </p>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--==================================================-->
	<!-- Start hendrio Top Menu section -->
	<!--==================================================-->




	<!--==================================================-->
	<!-- Start hendrio Main Menu  -->
	<!--==================================================-->
	<div id="sticky-header" class="hendrio_nav_manu">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-2">
					<div class="logo">
						<a class="logo_img" href="index.html" title="hendrio">
							<img src="assets/images/Image File/logo.png" alt="logo" width="200px" height="90px">
						</a>
						<a class="main_sticky" href="index.html" title="hendrio">
							<img src="assets/images/Image File/logo.png" alt="logo" width="200px" height="90px">
						</a>
					</div>
				</div>
				<div class="col-lg-10">
					<nav class="hendrio_menu">
						<ul class="nav_scroll">
							<li><a href="index.html">Home <span><!--<i class="fas fa-chevron-down"></i>--></span></a></li>
							<li><a href="about.html">About</a></li>
							<li><a href="#"><a href="service.html">Services <span></span></a></li>
							<li><a href="#">Info <span><i class="fas fa-chevron-down"></i></span></a>
								<ul class="sub-menu">
									<li><a href="team.html">Our Team</a></li>
									<li><a href="faq.html">FAQ</a></li>
								</ul>
							</li>
							<!---
							<li><a href="#">Shop <span><i class="fas fa-chevron-down"></i></span></a>
								<ul class="sub-menu">
									<li><a href="shop.html">Shop One</a></li>
									<li><a href="shop-2.html">Shop Two</a></li>
									<li><a href="shop-details.html">Shop Details</a></li>
								</ul>
							</li>
						-->
							<li><a href="#">Blog <span><i class="fas fa-chevron-down"></i></span></a>
								<ul class="sub-menu">
									<li><a href="blog_plumbing.html">Plumbing Tips</a></li>
									<li><a href="blog_home_repairing_tips.html">Renovation Tips</a></li>
									<li><a href="blog_electrical.html">Electrical Tips</a></li>		
								</ul>
							</li>
							<li><a href="contact.html">Contact</a></li>
						</ul>
						<div class="header-menu-right-btn">
							<!-- header button -->
							<div class="header-button">
								<a href="booking/booknow.html">Book Now</a>
								<!--<a href="contact.html">Get a Membership</a>-->
							</div>

							<div class="header-search-button search-box-outer">
								<a href="view_booking_customer.php" title="Booking"><i class="fa-regular fa-calendar-check" style="color: #ffffff;"></i></a>
							</div>
							
							<div class="header-search-button search-box-outer">
								<a href="profile/viewprofile.php"><i class='bx bx-user-circle'></i></a>
							</div>
						</div>
					</nav>
				</div>
			</div>
		</div>
	</div>

	<!-- hendrio Mobile Menu  -->
	<div class="mobile-menu-area sticky d-sm-block d-md-block d-lg-none ">
		<div class="mobile-menu">
			<nav class="hendrio_menu">
				<ul class="nav_scroll">
					<li><a href="index.html">Home <span></span></a></li>
					<li><a href="about.html">About Us</a></li>
					<li><a href="service.html">Services <span><i class="fas fa-chevron-down"></i></span></a></li>
					<li><a href="#">Pages <span><i class="fas fa-chevron-down"></i></span></a>
						<ul class="sub-menu">
							<li><a href="team.html">Our Team</a></li>
							<li><a href="faq.html">FAQ</a></li>
							<li><a href="contact.html">Contact Us</a></li>
						</ul>
					</li>
					<li><a href="#">Shop <span><i class="fas fa-chevron-down"></i></span></a>
						<ul class="sub-menu">
							<li><a href="shop.html">Shop One</a></li>
							<li><a href="shop-2.html">Shop Two</a></li>
							<li><a href="shop-details.html">Shop Details</a></li>
						</ul>
					</li>
					<li><a href="#">Blog <span><i class="fas fa-chevron-down"></i></span></a>
						<ul class="sub-menu">
							<li><a href="blog-details.html">Home Repairing Tips</a></li>
						</ul>
					</li>
					<li><a href="contact.html">Contact Us</a></li>
				</ul>
			</nav>
		</div>
	</div>
	<!--==================================================-->
	<!-- End hendrio Main Menu  -->
	<!--==================================================-->



	<!--==================================================-->
	<!-- Start hendrio Pricing Section  -->
	<!--==================================================-->

	<div class="pricing-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="hendrio-section-title text-center padding-lg">
						<h4> Manage Your Appointment </h4>
						<h1> Your Booking <span>History</span></h1>
					</div>
				</div>
			</div>

			<?php if (!empty($errorMessage)): ?>
    			<p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
					<?php else: ?>
						<?php if (!empty($bookings)): ?>
							<table border="1">
								<tr>
									<th>Transaction ID</th>
									<th>Technician Name</th>
									<th>Booking Date</th>
									<th>Booking Time</th>
									<th>Service Type</th>
									<th>Amount</th>
									<th>Payment Method</th>
									<th>Payment Status</th>    
									<th>Status</th>
									<th>Action</th> <!-- Column for buttons -->
								</tr>
								
								<?php foreach ($bookings as $booking): ?>
									<tr>
										<td><?php echo htmlspecialchars($booking['transaction_id']); ?></td>
										<td><?php echo !empty($booking['technician_name']) ? htmlspecialchars($booking['technician_name']) : 'Not Assigned'; ?></td>
										<td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
										<td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
										<td><?php echo htmlspecialchars($booking['service_type']); ?></td>
										<td>RM<?php echo htmlspecialchars($booking['amount']); ?></td>
										<td><?php echo htmlspecialchars($booking['payment_method']); ?></td>
										<td><?php echo htmlspecialchars($booking['payment_status']); ?></td>    

										<!-- Status Column with Dynamic Class -->
										<td class="<?php echo 'status-' . strtolower($booking['status']); ?>">
											<?php echo htmlspecialchars($booking['status']); ?>
										</td>

										<!-- Action Column -->
										<td>
											<?php if ($booking['status'] === 'Completed'): ?>
												<!-- Show "Feedback" Button if status is Completed -->
												<button class="feedback-btn" data-transaction-id="<?= htmlspecialchars($booking['transaction_id']); ?>">Feedback</button>


											<?php elseif ($booking['status'] === 'Canceled'): ?>
												<!-- No button if Canceled -->
											<?php else: ?>
												<!-- Show "Update" Button if status is Pending -->
												<form class="status-form" action="booking/update-status.php" method="POST" onsubmit="return false;">
													<input type="hidden" name="transaction_id" value="<?php echo htmlspecialchars($booking['transaction_id']); ?>">
													<button type="button" class="update-status">Update</button>
												</form>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</table>
						<?php else: ?>
							<p class="no-bookings">❌ No bookings found.</p>
						<?php endif; ?>
					<?php endif; ?>

			
		</div>
	</div>

	<!--==================================================-->
	<!-- End hendrio Pricing Section  -->
	<!--==================================================-->


	<!--==================================================-->
	<!-- Start hendrio Footer Section  -->
	<!--==================================================-->

	<div class="footer-section"> 
		<div class="container">
			<div class="row contact-section">
				<div class="col-lg-4 col-md-6">
					<div class="contact-informations">
						<div class="contact-icon">
							<img src="assets/images/resource/location.png" alt="">
						</div>
						<div class="contact-title-content">
							<h2 class="contact-title">Office Location</h2>
							<span class="contact-text">25, Jalan Larut, Titiwangsa Sentral <br> 50400 Kuala Lumpur</span>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="contact-informations">
						<div class="contact-icon">
							<img src="assets/images/resource/call.png" alt="">
						</div>
						<div class="contact-title-content">
							<h2 class="contact-title">Feel Free to Call Us</h2>
							<span class="contact-text2">+6014 - 2711 987</span>
							<span class="contact-text"></span>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="contact-informations">
						<div class="contact-icon">
							<img src="assets/images/resource/sms.png" alt="">
						</div>
						<div class="contact-title-content">
							<h2 class="contact-title">Send E-Mail</h2>
							<span class="contact-text">tanresourcesonlineservice<br>@gmail.com</span>
						</div>
					</div>
				</div>
			</div>
			<div class="row footer-bg">
				<div class="col-lg-3 col-md-6">
					<div class="widget widgets-company-info">
						<div class="dreamhub-logo">
						<a class="logo_thumb" href="index.html" title="dreamhub">
							<img src="assets/images/Image File/logo.png" alt="logo" width="230px" height="90px">
						</a>
					</div>
						<div class="company-info-desc">
							<p> Fix, Power, Transform – Solutions at Your Fingertips </p>
						</div>
						<div class="follow-company-icon">
							<a href="https://www.facebook.com/people/Tan-resources/100065744582741/"> <i class="fab fa-facebook-f"></i> </a>
							<a href="https://wa.me/60142711987"> <i class="fab fa-whatsapp"> </i> </a>
						</div>
					</div>					
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-nav-menu">
						<h4 class="widget-title">Popular Services</h4>
						<div class="menu-quick-link-content">
							<ul class="footer-menu">
								<li><a href="#"> Kitchen Plumbing </a></li>
								<li><a href="#"> Bathroom Plumbing </a></li>
								<li><a href="#"> System Checking </a></li>
								<li><a href="#"> Electronic Installation </a></li>
								<li><a href="#"> Home Renovations </a></li>
								<li><a href="#"> Interior Redesign </a></li>
							</ul>
						</div>
					</div>
				</div>	
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-nav-menu">
						<h4 class="widget-title"> Menu </h4>
						<div class="menu-quick-link-content">
							<ul class="footer-menu">
								<li><a href="about.html"> About Us </a></li>
								<li><a href="contact.html"> Contact Us </a></li>
								<li><a href="team.html"> Our Team </a></li>
								<li><a href="#"> Appoinment </a></li>
								<li><a href="faq.html"> FAQ </a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="widget widget-nav-thumb-post">
						<h4 class="widget-title"> Latest News </h4>
						<div class="footer-thumb-post style-two">
							
							<div class="footer-post-title">
								<h4><a href="../customer page/blog_home_repairing_tips.html">Top 5 Secrets Home Repairing Tips Discussions</a></h4>
								<span>10 July, 2024</span>
							</div>
						</div>
						<div class="footer-thumb-post">
							
							<div class="footer-post-title">
								<h4><a href="../customer page/blog_electrical.html">Ensure Your Home's Electrical Safety</a></h4>
								<span>10 July, 2024</span>
							</div>
						</div>

						<div class="footer-thumb-post">
							
							<div class="footer-post-title">
								<h4><a href="../customer page/blog_plumbing.html">Repairing Your Home Pipeline Using Equipments</a></h4>
								<span>10 July, 2024</span>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>	

	<div class="footer-bottom-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<div class="footer-bottom-content">
						<div class="footer-bottom-content-copy">
							<p>Copyright © 2025 <span>TROS</span>. All rights reserved.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="footer-bottom-menu text-right">
						<ul>
							<li><a href="#">Terms and Conditions Apply</a></li>
							<li><a href="#">Privacy Policy</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--==================================================-->
	<!-- End hendrio Footer Section  -->
	<!--==================================================-->



	<!-- Feedback Modal -->
	<div id="feedbackModal" class="modal">
		<div class="modal-content">
			<span class="close-btn">×</span>
			<h2 class="modal-title">Leave Your Feedback</h2>
			<form id="feedbackForm" method="POST" action="booking/submit_feedback.php" enctype="multipart/form-data">
				<input type="hidden" id="feedbackTransactionId" name="transaction_id" value="">
				<!-- Star Rating Section -->
				<div class="star-rating-section">
					<label for="rating" class="rating-label">Rate Your Experience:</label>
					<div class="stars">
						<span class="star" data-value="1">★</span>
						<span class="star" data-value="2">★</span>
						<span class="star" data-value="3">★</span>
						<span class="star" data-value="4">★</span>
						<span class="star" data-value="5">★</span>
					</div>
					<input type="hidden" name="rating" id="ratingValue" value="0" required>
				</div>
				<!-- Feedback Textarea -->
				<div class="feedback-section">
					<label for="feedbackText" class="feedback-label">Your Feedback:</label>
					<textarea name="feedback" id="feedbackText" rows="5" placeholder="Write your feedback here..." required></textarea>
				</div>
				<!-- Image Upload Section -->
				<div class="image-upload-section">
					<label for="feedbackImage" class="image-upload-label">Upload a Picture (Optional):</label>
					<input type="file" id="feedbackImage" name="feedback_image" accept="image/*">
					<div id="imagePreview" class="image-preview"></div>
				</div>
				<!-- Submit Button -->
				<button type="submit" class="submit-feedback-btn">Submit Feedback</button>
			</form>
		</div>
	</div>

<!-- Booking Status Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeStatusModal()">×</span>
        <h2 class="modal-title">Update Booking Status</h2>
        <p class="modal-subtitle">Select a new status:</p>

        <!-- Hidden Input to Store Transaction ID -->
        <input type="hidden" id="statusTransactionId" name="transaction_id">

        <!-- Booking Status Buttons -->
        <div class="status-options">
            <button class="status-btn completed" onclick="updateStatus('Completed')">✔ Completed</button>
            <button class="status-btn canceled" onclick="updateStatus('Canceled')">❌ Canceled</button>
        </div>

        <!-- Cancel Button -->
        <button class="cancel-btn" onclick="closeStatusModal()">🔙 Cancel</button>
    </div>
</div>






	<script src="assets/js/vendor/jquery-3.6.2.min.js"></script>

	<script src="assets/js/popper.min.js"></script>

	<script src="assets/js/bootstrap.min.js"></script>

	<script src="assets/js/owl.carousel.min.js"></script>

	<script src="assets/js/jquery.counterup.min.js"></script>

	<script src="assets/js/waypoints.min.js"></script>

	<script src="assets/js/wow.js"></script>

	<script src="assets/js/imagesloaded.pkgd.min.js"></script>

	<script src="venobox/venobox.js"></script>

	<script src="assets/js/animated-text.js"></script>

	<script src="venobox/venobox.min.js"></script>

	<script src="assets/js/isotope.pkgd.min.js"></script>

	<script src="assets/js/jquery.meanmenu.js"></script>

	<script src="assets/js/jquery.scrollUp.js"></script>

	<script src="assets/js/jquery.barfiller.js"></script>

	<script src="assets/js/theme.js"></script>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
    // Attach event listeners to all "Update Status" buttons
    document.querySelectorAll(".update-status").forEach(button => {
        button.addEventListener("click", function () {
            let transactionId = this.closest(".status-form").querySelector("input[name='transaction_id']").value;
            openStatusModal(transactionId);
        });
    });

    // Close Status Modal when clicking "X" or outside
    const statusModal = document.getElementById("statusModal");
    const statusCloseBtn = document.querySelector(".status-close-btn");

    if (statusCloseBtn) {
        statusCloseBtn.addEventListener("click", closeStatusModal);
    }

    window.addEventListener("click", function (event) {
        if (event.target === statusModal) {
            closeStatusModal();
        }
    });

    // Attach event listeners to all "Feedback" buttons
    document.querySelectorAll(".feedback-btn").forEach(button => {
        button.addEventListener("click", function () {
            let transactionId = this.getAttribute("data-transaction-id");

            if (!transactionId || transactionId === "undefined") {
                alert("Transaction ID is missing!");
                return;
            }

            openFeedbackModal(transactionId);
        });
    });

    // Close Feedback Modal when clicking "X" or outside
    const feedbackModal = document.getElementById("feedbackModal");
    const feedbackCloseBtn = document.querySelector(".close-btn");

    if (feedbackCloseBtn) {
        feedbackCloseBtn.addEventListener("click", closeFeedbackModal);
    }

    window.addEventListener("click", function (event) {
        if (event.target === feedbackModal) {
            closeFeedbackModal();
        }
    });

    // Star rating system
    document.querySelectorAll(".star").forEach(star => {
        star.addEventListener("click", function () {
            let ratingValue = this.dataset.value;
            document.getElementById("ratingValue").value = ratingValue;

            document.querySelectorAll(".star").forEach(s => s.classList.remove("selected"));
            for (let i = 0; i < ratingValue; i++) {
                document.querySelectorAll(".star")[i].classList.add("selected");
            }
        });
    });

    // Image preview for feedback
    document.getElementById("feedbackImage").addEventListener("change", function (event) {
        let reader = new FileReader();
        reader.onload = function () {
            let img = document.createElement("img");
            img.src = reader.result;
            img.style.width = "100px";
            img.style.marginTop = "10px";
            document.getElementById("imagePreview").innerHTML = "";
            document.getElementById("imagePreview").appendChild(img);
        };
        reader.readAsDataURL(event.target.files[0]);
    });
});

// Function to open the Feedback Modal
function openFeedbackModal(transactionId) {
    console.log("Opening feedback modal for Transaction ID:", transactionId); // Debugging step

    if (!transactionId || transactionId === "undefined") {
        alert("Error: Transaction ID is missing!");
        return;
    }

    document.getElementById("feedbackTransactionId").value = transactionId; // Set value in hidden input
    document.getElementById("feedbackModal").style.display = "flex";
}

// Function to close the Feedback Modal
function closeFeedbackModal() {
    document.getElementById("feedbackModal").style.display = "none";
}

// Function to open the Status Modal
function openStatusModal(transactionId) {
    document.getElementById("statusTransactionId").value = transactionId;
    document.getElementById("statusModal").style.display = "flex";
}

// Function to close the Status Modal
function closeStatusModal() {
    document.getElementById("statusModal").style.display = "none";
}

// Function to update status via AJAX
// Function to update status via AJAX
function updateStatus(newStatus) {
    let transactionId = document.getElementById("statusTransactionId").value;

    if (!transactionId) {
        alert("Transaction ID is missing!");
        return;
    }

    // Log the data being sent for debugging
    console.log("Updating status:", { transactionId, newStatus });

    let formData = new FormData();
    formData.append("transaction_id", transactionId);
    formData.append("status", newStatus);

    fetch("booking/update_status.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data); // Log full server response

        if (data.includes("success")) {
            alert(`Booking updated to ${newStatus}`);
            location.reload(); // Refresh page after successful update
        } else {
            alert("Failed to update status. Server response: " + data); // Show detailed error
        }
    })
    .catch(error => {
        console.error("Fetch Error:", error);
        alert("Something went wrong. Please try again.");
    });

    closeStatusModal();
}






</script>


</body>

</html>
