<?php
session_start();
include 'backend/database.php'; // Ensure correct path

if (!isset($_SESSION['user_id'])) {
  echo "User not logged in.";
  exit();

}
$user_id = $_SESSION['user_id'];

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

try {
  // Fetch user data from the database
  $sql = "SELECT name, email, phone_number, role , address ,  gender, profile_picture FROM users WHERE id = ?";
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
								<li class="breadcrumb-item active" aria-current="page">User Profile</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
								<a class="dropdown-item" href="javascript:;">Another action</a>
								<a class="dropdown-item" href="javascript:;">Something else here</a>
								<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
							</div>
						</div>
					</div>
				</div>
				<!--end breadcrumb-->
      

        <div class="card rounded-4">
          <div class="card-body p-4">
             <div class="position-relative mb-5">
              <img src="assets/images/gallery/profile-cover.png" class="img-fluid rounded-4 shadow" alt="">
              <div class="profile-avatar position-absolute top-100 start-50 translate-middle">
                <img src="<?php echo !empty($user['profile_picture']) ? 'uploads/' . $user['profile_picture'] : 'user-profile-default.webp'; ?>" 
                class=" rounded-circle p-1 bg-grd-danger shadow" 
                width="170" height="170" alt="">
              </div>
             </div>
              <div class="profile-info pt-5 d-flex align-items-center justify-content-between">
                <div class="">
                  <h3><?php echo htmlspecialchars($user['name'] ?? ''); ?></h3>
                  <p class="mb-0"><?php echo htmlspecialchars(strtoupper($user['role'] ?? '')); ?><br></p>
                </div>
                <div class="">
                  
                </div>
              </div>
              <div class="kewords d-flex align-items-center gap-3 mt-4 overflow-x-auto">
                 <button type="button" class="btn btn-sm btn-light rounded-5 px-4">TROS</button>
              </div>
          </div>
        </div>

        <div class="row">
           <div class="col-12 col-xl-8">
            <div class="card rounded-4 border-top border-4 border-primary border-gradient-1">
              <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="">
                    <h5 class="mb-0 fw-bold">My Profile</h5>
                  </div>
                  <div class="dropdown">
                    
                  </div>
                 </div>
								<form class="row g-4">
									<div class="col-md-12">
										<label for="input1" class="form-label">Name</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="input1" 
                        placeholder="Name" 
                        value="<?php echo htmlspecialchars(!empty($user['name']) ? $user['name'] : 'N/A'); ?>"
                        readonly>
									</div>
                  <div class="col-md-12">
										<label for="input2" class="form-label">Phone Number</label>
										<input type="text" 
                    class="form-control" 
                    id="input2" 
                    placeholder="Phone Number"
                    value="<?php echo htmlspecialchars(!empty($user['phone_number']) ? $user['phone_number'] : 'N/A'); ?>"
                    readonly>
									</div>
									<div class="col-md-12">
										<label for="input3" class="form-label">Email</label>
										<input type="email" 
                    class="form-control" 
                    id="input3" 
                    placeholder="N/A"
                    value="<?php echo htmlspecialchars(!empty($user['email']) ? $user['email'] : 'N/A'); ?>"
                    readonly>
                  </div>
									<div class="col-md-12">
										<label for="input5" class="form-label">Password</label>
										<input type="password" 
                    class="form-control" 
                    id="input5"
                    placeholder="********">
									</div>

									<div class="col-md-12">
                    <label for="input11" class="form-label">Address</label>
                    <textarea class="form-control" 
                    id="input11" 
                    placeholder="N/A" 
                    rows="4" 
                    cols="4"
                    readonly><?php echo htmlspecialchars(!empty($user['address']) ? $user['address'] : 'N/A'); ?></textarea>
                  </div>
									<div class="col-md-12">
										<div class="d-md-flex d-grid align-items-center gap-3">
										<button type="button" class="btn btn-grd-primary px-4" onclick="window.location.href='user-profile-edit.php';">Edit Profile</button>
										</div>
									</div>
								</form>
							</div>
            </div>
           </div>  
           <div class="col-12 col-xl-4">
            <div class="card rounded-4">
              <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="">
                    <h5 class="mb-0 fw-bold">About</h5>
                  </div>
                  <div class="dropdown">
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                 </div>
                 <div class="full-info">
                  <div class="info-list d-flex flex-column gap-3">
                    <div class="info-list-item d-flex align-items-center gap-3"><span class="material-icons-outlined">account_circle</span><p class="mb-0">Full Name:  <?php echo htmlspecialchars(!empty($user['name']) ? $user['name'] : 'N/A'); ?></p></div>
                    <div class="info-list-item d-flex align-items-center gap-3"><span class="material-icons-outlined">done</span><p class="mb-0">Status: active</p></div>
                    <div class="info-list-item d-flex align-items-center gap-3"><span class="material-icons-outlined">code</span><p class="mb-0">Role: <?php echo htmlspecialchars($user['role'] ?? 'N/A');?></p></div>
                    <div class="info-list-item d-flex align-items-center gap-3"><span class="material-icons-outlined">flag</span><p class="mb-0">Country: Malaysia</p></div>
                    <div class="info-list-item d-flex align-items-center gap-3"><span class="material-icons-outlined">send</span><p class="mb-0">Email:  <?php echo htmlspecialchars(!empty($user['email']) ? $user['email'] : 'N/A'); ?></p></div>
                    <div class="info-list-item d-flex align-items-center gap-3"><span class="material-icons-outlined">call</span><p class="mb-0">Phone: <?php echo htmlspecialchars(!empty($user['phone_number']) ? $user['phone_number'] : 'N/A'); ?></p></div>
                  </div>
                </div>
              </div>
            </div>
            

           </div>
        </div><!--end row-->
       


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
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/js/main.js"></script>


</body>

</html>