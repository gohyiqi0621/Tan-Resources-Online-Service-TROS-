<?php
session_start();
include 'backend/database.php'; // Include database connection

// Debugging: Print role to confirm it's correct
if (!isset($_SESSION['user_role'])) {
    die("❌ No user_role found! Please log in.");
} elseif ($_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('❌ Only admin can access this page!'); window.location.href = 'index.php';</script>";
    exit();
}

// Handle Add Technician
if (isset($_POST['add_technician'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $price = mysqli_real_escape_string($conn, $_POST['price']); // ✅ Fetch price

    // Debug: Check if file exists
    if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        die("❌ No file uploaded or file error! Error Code: " . $_FILES['image']['error']);
    }

    // Image Upload Handling
    $technician_pic = "";
    if (!empty($_FILES['image']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // ✅ WebP support
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // ✅ Check only file type
        if (in_array($image_ext, $allowed_extensions)) {
            $upload_dir = "backend/uploads/";

            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Unique filename to avoid conflicts
            $image_new_name = uniqid() . "_" . basename($image_name);
            $target = $upload_dir . $image_new_name;

            // Attempt file upload
            if (move_uploaded_file($image_tmp, $target)) {
                $technician_pic = $image_new_name;
            } else {
                die("❌ File upload failed! Check folder permissions.");
            }
        } else {
            die("❌ Invalid file type! Allowed: JPG, JPEG, PNG, GIF, WEBP");
        }
    }

    // Insert into Database
    $query = "INSERT INTO technicians (name, specialty, location, rating, phone_number, image, price) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssd", $name, $specialty, $location, $rating, $phone_number, $technician_pic, $price);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect
    echo "<script>
        alert('✅ Technician added successfully!');
        window.location.href = 'technician-list.php';
      </script>";
    exit();
}

$name = 'User'; // Default name
$profile_picture = 'user-profile-default.webp'; // Default profile picture

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Retrieve user details
    $stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $name = !empty($user['name']) ? $user['name'] : $name;
        $stored_path = $user['profile_picture'];
        if (!empty($stored_path)) {
            $profile_picture = (strpos($stored_path, 'http') === 0) ? $stored_path : 'uploads/' . $stored_path;
        }
    }
}

$name = 'User'; // Default name
$profile_picture = 'user-profile-default.webp'; // Default profile picture

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Retrieve user details
    $stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $name = !empty($user['name']) ? $user['name'] : $name;
        $stored_path = $user['profile_picture'];
        if (!empty($stored_path)) {
            $profile_picture = (strpos($stored_path, 'http') === 0) ? $stored_path : 'uploads/' . $stored_path;
        }
    }
}
?>


<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TROS | Admin Dashboard </title>
  <!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png">
  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet">
	<script src="assets/js/pace.min.js"></script>

  <!--plugins-->
  <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/metisMenu.min.css">
  <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/mm-vertical.css">
  <link rel="stylesheet" type="text/css" href="assets/plugins/simplebar/css/simplebar.css">
  <!--bootstrap css-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="sass/main.css" rel="stylesheet">
  <link href="sass/dark-theme.css" rel="stylesheet">
  <link href="sass/blue-theme.css" rel="stylesheet">
  <link href="sass/semi-dark.css" rel="stylesheet">
  <link href="sass/bordered-theme.css" rel="stylesheet">
  <link href="sass/responsive.css" rel="stylesheet">

</head>

<body>

  <!--start header-->
  <header class="top-header">
    <nav class="navbar navbar-expand align-items-center gap-4">
      <div class="btn-toggle">
        <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
      </div>

      <div class="card-body search-content"></div>
      
      <ul class="navbar-nav gap-1 nav-right-links align-items-center">
        <div class="notify-list"></div>
          
        <li class="nav-item dropdown">
          <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
             <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="rounded-circle p-1 border" width="45" height="45" alt="">
          </a>
          <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
            <a class="dropdown-item  gap-2 py-2" href="javascript:;">
              <div class="text-center">
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="Profile Picture"
                onerror="this.onerror=null; this.src='user-profile-default.webp';">
                <h5 class="mb-0 fw-bold">Hello, <?php echo htmlspecialchars($name); ?></h5>
              </div>
            </a>
            <hr class="dropdown-divider">
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="user-profile.php"><i
              class="material-icons-outlined">person_outline</i>Profile</a>
            <hr class="dropdown-divider">
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="../customer page/register_login_page/backend/logout.php">
              <i class="material-icons-outlined">power_settings_new</i> Logout
          </a>
          
          </div>
        </li>
      </ul>

    </nav>
  </header>
  <!--end top header-->


   <!--start sidebar-->
   <aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div class="logo-icon">
        <img src="../customer page/assets/images/fav-icon/icon.png" class="logo-img" alt="" style="margin-right: 10px";>
      </div>
      <div class="logo-name flex-grow-1">
        <h5 class="mb-0">TROS</h5>
      </div>
      <div class="sidebar-close">
        <span class="material-icons-outlined">close</span>
      </div>
    </div>
    <div class="sidebar-nav">
        <!--navigation-->
        <ul class="metismenu" id="sidenav">
          <li>
            <a href="index.php" >
              <div class="parent-icon"><i class="material-icons-outlined">home</i>
              </div>
              <div class="menu-title">Dashboard</div>
            </a>
          </li>
          <li>
            <a href="user-profile.php">
              <div class="parent-icon"><i class="material-icons-outlined">person</i>
              </div>
              <div class="menu-title">User Profile</div>
            </a>
          </li>

          <li class="menu-label">View</li>

          <li>
            <a href="view-contact.php">
              <div class="parent-icon"><i class="material-icons-outlined">view_agenda</i>
              </div>
              <div class="menu-title">Contacts</div>
            </a>
          </li>

          <li>
            <a href="view-feedback.php">
              <div class="parent-icon"><i class="material-icons-outlined">help_outline</i>
              </div>
              <div class="menu-title">Feedback</div>
            </a>
          </li>

          <li>
            <a href="view-receipt.php">
              <div class="parent-icon"><i class="material-icons-outlined">inventory_2</i>
              </div>
              <div class="menu-title">Receipt</div>
            </a>
          </li>

          <li>
            <a href="view-appointment.php">
              <div class="parent-icon"><i class="material-icons-outlined">description</i>
              </div>
              <div class="menu-title">Appointment</div>
            </a>
          </li>

          <li>
            <a href="sales-report.php">
              <div class="parent-icon"><i class="material-icons-outlined">support</i>
              </div>
              <div class="menu-title">Sales Report</div>
            </a>
          </li>

          <li class="menu-label">Edit Character</li>

          

          <li>
            <a href="customer-list.php">
              <div class="parent-icon"><i class="material-icons-outlined">shopping_bag</i>
              </div>
              <div class="menu-title">Customer</div>
            </a>
          </li>

          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">face_5</i>
              </div>
              <div class="menu-title">Technicians</div>
            </a>
            <ul>
              <li><a href="technician-list.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>View Technician</a></li>
              <li><a href="add-technician.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>Add Technician</a></li>
            </ul>
          </li>

          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">person</i>
              </div>
              <div class="menu-title">Staff</div>
            </a>
            <ul>
              <li><a href="staff-list.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>View Staff</a></li>
                <li><a href="add-staff.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>Add Staff</a></li>
            </ul>
          </li>
          
          
        <!--end navigation-->
    </div>
  </aside>
<!--end sidebar-->


  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Components</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Add Technician</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">
            <button class="btn btn-primary px-4" onclick="window.location.href='technician-list.php'">View Technician</button>
						</div>
					</div>
				</div>
				<!--end breadcrumb-->
      

          <div class="items" data-group="test">
          <div class="card">
            <div class="card-body">
              <h2 class="font-weight-light text-center py-3">Adding Technician</h2>
              <div class="item-content">
                <form action="" method="POST" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="inputName3" class="form-label">Name</label>
                    <input type="text" class="form-control" id="inputName3" name="name" placeholder="Name" required>
                  </div>

                  <div class="mb-3">
                    <label for="inputPhoneNumber3" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="inputPhoneNumbe3" name="phone_number" placeholder="Phone Number" required>
                  </div>
                  
                  <div class="mb-3">
                    <label for="inputSpecialty3" class="form-label">Specialty</label>
                    <select class="form-control" id="inputSpecialty3" name="specialty" required>
                      <option value="" disabled selected>Select Specialty</option>
                      <option value="Plumbing">Plumbing</option>
                      <option value="Renovations">Renovations</option>
                      <option value="Electrical">Electrical</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="inputLocation3" class="form-label">Location</label>
                    <select class="form-control" id="inputLocation3" name="location" required>
                      <option value="" disabled selected>Select Location</option>
                      <option value="Titiwangsa">Titiwangsa</option>
                      <option value="Setapak">Setapak</option>
                      <option value="Bukit Bintang">Bukit Bintang</option>
                      <option value="Kampung Baru">Kampung Baru</option>
                      <option value="Cheras">Cheras</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="inputRating3" class="form-label">Basic Price</label>
                    <input type="text" step="0.2" class="form-control" id="inputPrice3" name="price" placeholder="Basic Price" required>
                  </div>

                  <div class="mb-3">
                    <label for="inputRating3" class="form-label">Rating</label>
                    <input type="number" step="0.1" class="form-control" id="inputRating3" name="rating" placeholder="Rating" required>
                  </div>

                  <div class="mb-3">
                    <label for="inputImage3" class="form-label">Profile Image</label>
                    <input type="file" class="form-control" id="inputImage3" name="image" accept="image/*" required>
                </div>

                  <button type="submit" name="add_technician" class="btn btn-success">Add Technician</button>
                </form>
              </div>
            </div>
          </div>
        </div>

    </div>
  </main>
  <!--end main wrapper-->


    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

     <!--start footer-->
     <footer class="page-footer">
       <p class="mb-0">Copyright © 2025. Tan Resources Online Service.</p>
    </footer>
    <!--top footer-->

  
 <!--start switcher-->
 <button class="btn btn-grd btn-grd-primary position-fixed bottom-0 end-0 m-3 d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
    <i class="material-icons-outlined">tune</i>Customize
  </button>
  
  <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="staticBackdrop">
    <div class="offcanvas-header border-bottom h-70">
      <div class="">
        <h5 class="mb-0">Theme Customizer</h5>
        <p class="mb-0">Customize your theme</p>
      </div>
      <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
        <i class="material-icons-outlined">close</i>
      </a>
    </div>
    <div class="offcanvas-body">
      <div>
        <p>Theme variation</p>

        <div class="row g-3">
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="BlueTheme" checked>
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="BlueTheme">
              <span class="material-icons-outlined">contactless</span>
              <span>Blue</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="LightTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="LightTheme">
              <span class="material-icons-outlined">light_mode</span>
              <span>Light</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="DarkTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="DarkTheme">
              <span class="material-icons-outlined">dark_mode</span>
              <span>Dark</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="SemiDarkTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="SemiDarkTheme">
              <span class="material-icons-outlined">contrast</span>
              <span>Semi Dark</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="BoderedTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="BoderedTheme">
              <span class="material-icons-outlined">border_style</span>
              <span>Bordered</span>
            </label>
          </div>
        </div><!--end row-->

      </div>
    </div>
  </div>
  <!--start switcher-->

  <!--bootstrap js-->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <!--plugins-->
  <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="assets/plugins/metismenu/metisMenu.min.js"></script>
  <script src="assets/plugins/form-repeater/repeater.js"></script>

  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/js/main.js"></script>


</body>

</html>