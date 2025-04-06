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
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css" type="text/css" media="all">
	<!-- carousel CSS -->
	<link rel="stylesheet" href="../assets/css/owl.carousel.min.css" type="text/css" media="all">
	<!-- animate CSS -->
	<link rel="stylesheet" href="../assets/css/animate.css" type="text/css" media="all">
	<!-- animated-text CSS -->
	<link rel="stylesheet" href="../assets/css/animated-text.css" type="text/css" media="all">
	<!-- font-awesome CSS -->
	<link rel="stylesheet" href="../assets/css/all.min.css" type="text/css" media="all">
	<!-- font-flaticon CSS -->
	<link rel="stylesheet" href="../assets/css/flaticon.css" type="text/css" media="all">
	<!-- theme-default CSS -->
	<link rel="stylesheet" href="../assets/css/theme-default.css" type="text/css" media="all">
	<!-- meanmenu CSS -->
	<link rel="stylesheet" href="../assets/css/meanmenu.min.css" type="text/css" media="all">
	<!-- transitions CSS -->
	<link rel="stylesheet" href="../assets/css/owl.transitions.css" type="text/css" media="all">
	<!-- venobox CSS -->
	<link rel="stylesheet" href="../venobox/venobox.css" type="text/css" media="all">
	<!-- bootstrap icons -->
	<link rel="stylesheet" href="../assets/css/bootstrap-icons.css" type="text/css" media="all">
	<!-- Main Style CSS -->
	<link rel="stylesheet" href="../assets/css/style.css" type="text/css" media="all">  
	<!-- responsive CSS -->
	<link rel="stylesheet" href="../assets/css/responsive.css" type="text/css" media="all">
	<!-- modernizr js -->
	<script src="../assets/js/vendor/modernizr-3.5.0.min.js"></script>
	<!--boxicons CSS-->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>	
	<!-- rprofile CSS -->
	<link rel="stylesheet" href="style.css" type="text/css" media="all">

	<!--boxicons CSS-->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<!--font awesome-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>

<body>

	<?php
		session_start();
		include_once '../assets/database/database1/database.php';

		if (!isset($_SESSION['user_id'])) {
			echo "User not logged in.";
			exit();
		}
		$user_id = $_SESSION['user_id'];

		try {
			// Fetch user data from the database
			$sql = "SELECT name, email, phone_number, address, gender, profile_picture FROM users WHERE id = ?";
			$stmt = $conn->prepare($sql);
			if (!$stmt) {
				throw new Exception("Error preparing the statement: " . $conn->error);
			}
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$user = $result->fetch_assoc();
			} else {
				throw new Exception("User not found.");
			}
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			exit();
		}
    ?>

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
							<img src="../assets/images/Image File/logo.png" alt="logo" width="200px" height="90px">
						</a>
						<a class="main_sticky" href="index.html" title="hendrio">
							<img src="../assets/images/Image File/logo.png" alt="logo" width="200px" height="90px">
						</a>
					</div>
				</div>
				<div class="col-lg-10">
					<nav class="hendrio_menu">
						<ul class="nav_scroll">
							<li><a href="../index.html">Home <span><!--<i class="fas fa-chevron-down"></i>--></span></a></li>
							<li><a href="../about.html">About</a></li>
							<li><a href="#"><a href="../service.html">Services <span></span></a></li>
							<li><a href="#">Info <span><i class="fas fa-chevron-down"></i></span></a>
								<ul class="sub-menu">
									<li><a href="../team.html">Our Team</a></li>
									<li><a href="../faq.html">FAQ</a></li>
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
									<li><a href="../blog_plumbing.html">Plumbing Tips</a></li>
									<li><a href="../blog_home_repairing_tips.html">Renovation Tips</a></li>
									<li><a href="../blog_electrical.html">Electrical Tips</a></li>		
								</ul>
							</li>
							<li><a href="../contact.html">Contact</a></li>
						</ul>
						<div class="header-menu-right-btn">
							<!-- header button -->
							<div class="header-button">
								<a href="../booking/booknow.html">Book Now</a>
								<!--<a href="contact.html">Get a Membership</a>-->
							</div>

							<div class="header-search-button search-box-outer">
								<a href="../view_booking_customer.php" title="Booking"><i class="fa-regular fa-calendar-check" style="color: #ffffff;"></i></a>
							</div>
							
							<div class="header-search-button search-box-outer">
								<a href="../profile/viewprofile.php"><i class='bx bx-user-circle'></i></a>
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
					<li><a href="../index.html">Home <span></span></a></li>
					<li><a href="../about.html">About Us</a></li>
					<li><a href="../service.html">Services <span><i class="fas fa-chevron-down"></i></span></a></li>
					<li><a href="#">Pages <span><i class="fas fa-chevron-down"></i></span></a>
						<ul class="sub-menu">
							<li><a href="../team.html">Our Team</a></li>
							<li><a href="../faq.html">FAQ</a></li>
							<li><a href="../contact.html">Contact Us</a></li>
						</ul>
					</li>
					<li><a href="#">Blog <span><i class="fas fa-chevron-down"></i></span></a>
						<ul class="sub-menu">
							<li><a href="../blog-details.html">Home Repairing Tips</a></li>
						</ul>
					</li>
					<li><a href="../contact.html">Contact Us</a></li>
				</ul>
			</nav>
		</div>
	</div>
	<!--==================================================-->
	<!-- End hendrio Main Menu  -->
	<!--==================================================-->
        
        <div class="container2">
            <div class="profile-section">
                <!-- Profile Picture -->
                <div class="profile-picture-container">
                    <img src="<?php echo !empty($user['profile_picture']) ? 'uploads/' . $user['profile_picture'] : '../../staff&adminPage/user-profile-default.webp'; ?>" alt="Profile Picture" class="profile-picture">
                    <h3><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></h3>
                    <p><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
                </div>
                <div class="profile-details">
                    <div class="details-header">
						<h2 class="mydetails" id="detailHeader"><b>MY DETAILS</b></h2>
                        <p class="explain">Feel free to edit any of your details below so your account is up to date.</p>
                    </div>
                    <!-- Personal Details -->
                    <section class="details">
                        <h3>PERSONAL INFORMATION</h3>
                        <div class="detail-item">
                            <label>Name:</label>
                            <span><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?> <a href="editProfile.php">Edit</a></span>
                        </div>
                        <div class="detail-item">
                            <label>Email:</label>
                            <span><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?> <a href="editProfile.php">Edit</a></span>
                        </div>
						<div class="detail-item">
                            <label>Phone Number:</label>
                            <span><?php echo htmlspecialchars($user['phone_number'] ?? 'N/A'); ?> <a href="editProfile.php">Edit</a></span>
                        </div>
                        <div class="detail-item">
                            <label>Address:</label>
                            <span><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?> <a href="editProfile.php">Edit</a></span>
                        </div>
                        <div class="detail-item">
                            <label>Gender:</label>
                            <span><?php echo htmlspecialchars($user['gender'] ?? 'N/A'); ?> <a href="editProfile.php">Edit</a></span>
                        </div>
                    </section>
                    <!-- Login Details -->
                    <section class="login-details">
                        <h3>LOGIN INFORMATION</h3>
                        <div class="detail-item">
                            <label>Email:</label>
                            <span><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?> <a href="editProfile.php">Edit</a></span>
                        </div>
                        <div class="detail-item">
                            <label>Password:</label>
                            <span>********* <a href="editProfile.php">Edit</a></span>
                        </div>
                    </section>
                    <!-- Account Actions -->
                    <section class="account-actions">
                        <button type="button" onclick="location.href='../register_login_page/backend/logout.php'">Log Out</button>
                        <button type="button" onclick="confirmDelete()">Delete Account</button>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <script>
		function confirmDelete() {
			if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
				let xhr = new XMLHttpRequest();
				xhr.open("POST", "delete_account.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						alert(xhr.responseText); // Show response message
						window.location.href = "../register_login_page/backend/logout.php"; // Redirect to logout page after deletion
					}
				};

				xhr.send("delete_account=true");
			}
		}
		</script>

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
							<img src="../assets/images/Image File/logo.png" alt="logo" width="230px" height="90px">
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
						<h4 class="widget-title"> Useful Links </h4>
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


	<script src="../assets/js/vendor/jquery-3.6.2.min.js"></script>

	<script src="../assets/js/popper.min.js"></script>

	<script src="../assets/js/bootstrap.min.js"></script>

	<script src="../assets/js/owl.carousel.min.js"></script>

	<script src="../assets/js/jquery.counterup.min.js"></script>

	<script src="../assets/js/waypoints.min.js"></script>

	<script src="../assets/js/wow.js"></script>

	<script src="../assets/js/imagesloaded.pkgd.min.js"></script>

	<script src="../venobox/venobox.js"></script>

	<script src="../assets/js/animated-text.js"></script>

	<script src="../venobox/venobox.min.js"></script>

	<script src="../assets/js/isotope.pkgd.min.js"></script>

	<script src="../assets/js/jquery.meanmenu.js"></script>

	<script src="../assets/js/jquery.scrollUp.js"></script>

	<script src="../assets/js/jquery.barfiller.js"></script>

	<script src="../assets/js/theme.js"></script>

</body>

</html>