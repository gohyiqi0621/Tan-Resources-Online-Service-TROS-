<?php
// Start session
session_start();

// Include database connection
include 'booking/database.php';

// Get user input safely
$service  = isset($_POST['service'])  ? trim($_POST['service'])  : '';
$date     = isset($_POST['date'])     ? trim($_POST['date'])     : '';
$time     = isset($_POST['time'])     ? trim($_POST['time'])     : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';

// Convert time format to match MySQL TIME type
$timeFormatted = date("H:i:s", strtotime($time));

// Check if inputs are empty
if (empty($service) || empty($location) || empty($date) || empty($timeFormatted)) {
    die("<p class='text-danger'>Error: Missing input values.</p>");
}

// Store date and time in session
$_SESSION['booking_date'] = $date;
$_SESSION['booking_time'] = $timeFormatted;
$_SESSION['service_type'] = $service;

// ✅ Corrected query to use the actual primary key name of technicians table
$query = "SELECT t.* 
          FROM technicians t
          LEFT JOIN technician_availability ta ON t.id = ta.technician_id AND ta.booking_date = ?
          WHERE LOWER(t.specialty) LIKE LOWER(CONCAT('%', ?, '%'))
          AND LOWER(t.location) = LOWER(?)
          AND (t.date_available = ? OR t.date_available = 'any' OR t.date_available IS NULL)
          AND TIME(?) BETWEEN TIME(t.time_start) AND TIME(t.time_end)
          AND t.is_booked = 0
          AND ta.technician_id IS NULL  -- Exclude booked technicians
          ORDER BY t.price ASC"; 

// Prepare statement
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error in query preparation: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("sssss", $date, $service, $location, $date, $timeFormatted);

// Execute query
$stmt->execute();
$result = $stmt->get_result();

// Store technicians in an array
$technicians = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $technicians[] = $row;
    }
} else {
    die("<p class='text-danger'>No available technicians for your selected date, time, and location.</p>");
}

// Close statement
$stmt->close();
$conn->close();
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
	<!-- modernizr js -->
	<script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>
	<!--boxicons CSS-->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<!--font awesome-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
	

	<style>
/* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background: #E6F0FA;
    color: #333;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

/* Heading */
h2.text-center {
    margin-bottom: 50px;
    font-size: 36px;
    color: #1a1a1a;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    position: relative;
    font-family: 'Playfair Display', serif;
}

h2.text-center::after {
    content: '';
    width: 80px;
    height: 5px;
    background: linear-gradient(90deg, #062462, #0a3d8a);
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 3px;
}

/* Technician Container */
.row.technician-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 40px;
    padding: 50px 20px;
}

/* Technician Card Layout */
.col-lg-4.col-md-6.col-sm-12.d-flex.justify-content-center {
    display: flex;
    flex: 1 1 300px;
    max-width: 33.3333%;
    justify-content: center;
}

@media (max-width: 992px) {
    .col-lg-4.col-md-6.col-sm-12.d-flex.justify-content-center {
        max-width: 50%;
    }
}

@media (max-width: 768px) {
    .col-lg-4.col-md-6.col-sm-12.d-flex.justify-content-center {
        max-width: 100%;
    }
}

/* Technician Card */
.technician-card {
    width: 100%;
    max-width: 360px;
    border-radius: 20px;
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    padding: 30px;
    text-align: center;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
    transition: all 0.5s ease-in-out;
    border: 2px solid #062462;
}

/* Background Pattern */
.technician-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('https://www.transparenttextures.com/patterns/subtle-white-feathers.png');
    opacity: 0.03;
    z-index: -1;
}

/* Inner Glow */
.technician-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    background: linear-gradient(145deg, #ffffff, #f0f4f8);
}

/* Name Tag Clip */
.technician-card .name-tag-clip {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 25px;
    background: #062462;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
}

.technician-card .name-tag-clip::before {
    content: '';
    position: absolute;
    top: 6px;
    left: 6px;
    right: 6px;
    height: 3px;
    background: #ffffff;
    opacity: 0.5;
}

/* Availability Badge */
.technician-card .availability-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.technician-card .availability-badge.available {
    background: #062462;
    color: white;
}

.technician-card .availability-badge.not-available {
    background: #dc3545;
    color: white;
}

.technician-card .availability-badge:hover {
    transform: scale(1.1);
}

/* Image Wrapper */
.technician-card .image-wrapper {
    position: relative;
    width: 140px;
    height: 140px;
    margin: 20px auto 15px;
}

.technician-card img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #e8ecef;
    transition: all 0.4s ease;
}

.technician-card:hover img {
    border-color: #062462;
    transform: scale(1.05);
}

/* Technician Name */
.technician-card h5 {
    margin: 0 0 10px;
    font-size: 26px;
    color: #1a1a1a;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-family: 'Playfair Display', serif;
    transition: color 0.3s ease;
}

.technician-card:hover h5 {
    color: #062462;
}

/* Decorative Divider */
.technician-card .divider {
    width: 50px;
    height: 2px;
    background: #062462;
    margin: 0 auto 20px;
    border-radius: 1px;
    position: relative;
}

/* Icon List */
.icon-list {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
}

.icon-item {
    display: flex;
    align-items: center;
    justify-content: flex-start; /* Align items to the start for consistency */
    margin: 8px 0;
    font-size: 15px;
    color: #4a4a4a;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.95);
    padding: 10px 15px;
    border-radius: 10px;
    width: 100%;
    max-width: 300px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}

.icon-item i {
    margin-right: 12px; /* Increase margin for better spacing */
    font-size: 18px;
    color: #062462;
    transition: transform 0.3s ease;
    width: 20px; /* Fixed width for icons to ensure consistency */
    text-align: center; /* Center the icon within its space */
}

.icon-item span {
    flex: 1; /* Allow the text to take up remaining space */
    display: flex;
    justify-content: space-between; /* Balance the text and value */
}

.icon-item span strong {
    margin-right: 5px; /* Add a small margin between the label and value */
}

.icon-item:hover {
    color: #1a1a1a;
    background: rgba(232, 236, 239, 0.6);
    transform: translateX(3px);
}

.icon-item:hover i {
    transform: scale(1.2);
}

/* Book Now Button */
.technician-card .btn.btn-primary.w-100 {
    display: block;
    padding: 12px;
    background: #062462;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.4s ease;
    box-shadow: 0 4px 12px rgba(6, 36, 98, 0.2);
    position: relative;
    overflow: hidden;
}

.technician-card .btn.btn-primary.w-100::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.technician-card .btn.btn-primary.w-100:hover::before {
    width: 300px;
    height: 300px;
}

.technician-card .btn.btn-primary.w-100:hover {
    background: #0a3d8a;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(6, 36, 98, 0.3);
}

/* Animations */
@keyframes glowing {
    0% { box-shadow: 0 0 8px rgba(6, 36, 98, 0.2); }
    50% { box-shadow: 0 0 20px rgba(6, 36, 98, 0.4); }
    100% { box-shadow: 0 0 8px rgba(6, 36, 98, 0.2); }
}

.technician-card:hover {
    animation: glowing 1.5s infinite;
}

/* Fade-in Animation */
.animate__animated.animate__fadeInUp {
    --animate-duration: 0.8s;
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
							<img src="assets/images/Image File/logo.png" alt="logo" width="200px" height="90px">	<!--logo header-->
						</a>
						<a class="main_sticky" href="index.html" title="hendrio">
							<img src="assets/images/Image File/logo.png" alt="logo" width="200px" height="90px">	<!--logo header-->
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
							<div class="header-search-button search-box-outer">
								<a href="view_booking_customer.php" title="Schedule"><i class="fa-regular fa-calendar-check" style="color: #ffffff;"></i></a>
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
	<!-- Start hendrio Hero Section  -->
	<!--==================================================-->

	<div class="container mt-5">
    <h2 class="text-center">Available Technicians</h2>
    <div class="row technician-container">
        <?php foreach ($technicians as $row): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center">
                <div class="technician-card animate__animated animate__fadeInUp">
                    <!-- Name Tag Clip -->
                    <div class="name-tag-clip"></div>
                    <!-- Availability Badge -->
                    <div class="availability-badge <?php echo ($row['is_booked'] == 0) ? 'available' : 'not-available'; ?>">
                        <span><?php echo ($row['is_booked'] == 0) ? "Available" : "Not Available"; ?></span>
                    </div>
                    <div class="image-wrapper">
                        <img src="../staff&adminPage/backend/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Technician Image">
                    </div>
                    <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                    <!-- Decorative Divider -->
                    <div class="divider"></div>
                    <div class="icon-list">
                        <div class="icon-item">
                            <i class="fas fa-wrench"></i> 
                            <span><strong>Specialty:</strong> <?php echo htmlspecialchars($row['specialty']); ?></span>
                        </div>
                        <div class="icon-item">
                            <i class="fas fa-map-marker-alt"></i> 
                            <span><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></span>
                        </div>
                        <div class="icon-item rating-item">
                            <i class="fas fa-star"></i> 
                            <span><strong>Rating:</strong> <?php echo htmlspecialchars($row['rating']); ?> ⭐</span>
                        </div>
                        <div class="icon-item">
                            <i class="fas fa-dollar-sign"></i> 
                            <span><strong>Price: RM</strong> <?php echo htmlspecialchars($row['price']); ?></span>
                        </div>
                    </div>
                    <!-- "Book Now" button -->
                    <form action="booking/book.php" method="POST">
                        <input type="hidden" name="technician_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="date" value="<?php echo $date; ?>">
                        <input type="hidden" name="time" value="<?php echo $timeFormatted; ?>">
                        <button type="submit" class="btn btn-primary w-100">Book Now</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
	<!--==================================================-->
	<!-- End hendrio Hero Section  -->
	<!--==================================================-->


	<!--==================================================-->
	<!-- Start hendrio Footer Section  -->
	<!--==================================================-->

	<div class="footer-section"> 
		<div class="container">
			
			<div class="row footer-bg">
				<div class="col-lg-3 col-md-6">
					<div class="widget widgets-company-info">
						<div class="dreamhub-logo">
						<a class="logo_thumb" href="index.html" title="dreamhub">
							<img src="assets/images/Image File/logo.png" alt="logo" width="230px" height="90px"> <!--logo footer-->
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
								<h4><a href="../customer page/blog_electrical.html">How to Keep Your Home’s Electrical System Safe and Reliable</a></h4>
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


	<script>
	document.addEventListener("DOMContentLoaded", function () {
		const technicianCards = document.querySelectorAll(".technician-card");

		technicianCards.forEach(card => {
			card.addEventListener("mouseenter", function () {
				const statusText = card.querySelector(".status");
				if (statusText.classList.contains("available")) {
					statusText.style.color = "#218838";
					statusText.style.fontWeight = "bold";
				} else {
					statusText.style.color = "#c82333";
					statusText.style.fontWeight = "bold";
				}
			});

			card.addEventListener("mouseleave", function () {
				const statusText = card.querySelector(".status");
				statusText.style.color = "";
				statusText.style.fontWeight = "";
			});
		});
	});
	</script>


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

</body>

</html>