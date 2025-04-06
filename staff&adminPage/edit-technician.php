<?php
include 'backend/database.php'; // Ensure your database connection is included
session_start(); // Ensure session is started

// First, get the logged-in user's information
$user_name = 'User'; // Default name for logged-in user
$user_profile_picture = 'user-profile-default.webp'; // Default profile picture for logged-in user

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 && $user = $result->fetch_assoc()) {
        $user_name = !empty($user['name']) ? $user['name'] : $user_name;
        $stored_path = $user['profile_picture'];
        if (!empty($stored_path)) {
            // Check if the file exists in the uploads directory
            $upload_path = 'uploads/' . $stored_path;
            if (file_exists($upload_path)) {
                $user_profile_picture = $upload_path;
            } elseif (strpos($stored_path, 'http') === 0) {
                $user_profile_picture = $stored_path;
            }
        }
    }
    $stmt->close();
} else {
    // Redirect to login if user is not logged in
    header("Location: ../customer page/register_login_page/login.php");
    exit();
}

// Then, get the technician's information
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM technicians WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tech_name = $row['name'] ?? '';
        $phone_number = $row['phone_number'] ?? '';
        $specialty = $row['specialty'] ?? '';
        $location = $row['location'] ?? '';
        $price = $row['price'] ?? '';
        $rating = $row['rating'] ?? '';
        // Ensure the image path matches the folder structure used in technician-list.php
        $tech_profile_picture = !empty($row['image']) ? $row['image'] : 'default.jpg';
    } else {
        die("❌ Technician not found.");
    }

    $stmt->close();
} else {
    die("❌ No technician ID provided or invalid ID.");
}
?>

<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TROS | Edit Technician</title>
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
          <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
             <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" class="rounded-circle p-1 border" width="45" height="45" alt="User Profile">
          </a>
          <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
            <a class="dropdown-item gap-2 py-2" href="javascript:;">
              <div class="text-center">
                <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="User Profile"
                onerror="this.onerror=null; this.src='user-profile-default.webp';">
                <h5 class="mb-0 fw-bold">Hello, <?php echo htmlspecialchars($user_name); ?></h5>
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
        <img src="../customer page/assets/images/fav-icon/icon.png" class="logo-img" alt="" style="margin-right: 10px">
      </div>
      <div class="logo-name flex-grow-1">
        <h5 class="mb-0">TROS</h5>
      </div>
      <div class="sidebar-close">
        <span class="material-icons-outlined">close</span>
      </div>
    </div>
    <div class="sidebar-nav">
        <ul class="metismenu" id="sidenav">
          <li>
            <a href="index.php">
              <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
              <div class="menu-title">Dashboard</div>
            </a>
          </li>
          <li>
            <a href="user-profile.php">
              <div class="parent-icon"><i class="material-icons-outlined">person</i></div>
              <div class="menu-title">User Profile</div>
            </a>
          </li>
          <li class="menu-label">View</li>
          <li>
            <a href="view-contact.php">
              <div class="parent-icon"><i class="material-icons-outlined">view_agenda</i></div>
              <div class="menu-title">Contacts</div>
            </a>
          </li>
          <li>
            <a href="view-feedback.php">
              <div class="parent-icon"><i class="material-icons-outlined">help_outline</i></div>
              <div class="menu-title">Feedback</div>
            </a>
          </li>
          <li>
            <a href="view-receipt.php">
              <div class="parent-icon"><i class="material-icons-outlined">inventory_2</i></div>
              <div class="menu-title">Receipt</div>
            </a>
          </li>
          <li>
            <a href="view-appointment.php">
              <div class="parent-icon"><i class="material-icons-outlined">description</i></div>
              <div class="menu-title">Appointment</div>
            </a>
          </li>
          <li>
            <a href="sales-report.php">
              <div class="parent-icon"><i class="material-icons-outlined">support</i></div>
              <div class="menu-title">Sales Report</div>
            </a>
          </li>
          <li class="menu-label">Edit Character</li>
          <li>
            <a href="customer-list.php">
              <div class="parent-icon"><i class="material-icons-outlined">shopping_bag</i></div>
              <div class="menu-title">Customer</div>
            </a>
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">face_5</i></div>
              <div class="menu-title">Technicians</div>
            </a>
            <ul>
              <li><a href="technician-list.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>View Technician</a></li>
              <li><a href="add-technician.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>Add Technician</a></li>
            </ul>
          </li>
          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">person</i></div>
              <div class="menu-title">Staff</div>
            </a>
            <ul>
              <li><a href="staff-list.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>View Staff</a></li>
              <li><a href="add-staff.php" target="_blank"><i class="material-icons-outlined">arrow_right</i>Add Staff</a></li>
            </ul>
          </li>
        </ul>
    </div>
  </aside>
  <!--end sidebar-->

  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Technicians</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Technician</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
          <div class="btn-group">
            
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
              <a class="dropdown-item" href="javascript:;">Action</a>
              <a class="dropdown-item" href="javascript:;">Another action</a>
              <a class="dropdown-item" href="javascript:;">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:;">Separated link</a>
            </div>
          </div>
        </div>
      </div>
      <!--end breadcrumb-->

      <div class="items" data-group="test">
        <div class="card">
          <div class="card-body">
            <h2 class="font-weight-light text-center py-3">Edit Technician</h2>
            <div class="item-content">
              <form action="backend/update-technician.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="mb-3">
                  <label for="inputName3" class="form-label">Name</label>
                  <input type="text" class="form-control" id="inputName3" name="name" value="<?php echo htmlspecialchars($tech_name); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="inputPhoneNumber3" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="inputPhoneNumber3" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="inputSpecialty3" class="form-label">Specialty</label>
                  <select class="form-control" id="inputSpecialty3" name="specialty" required>
                    <option value="" disabled>Select Specialty</option>
                    <option value="Plumbing" <?php if ($specialty == 'Plumbing') echo 'selected'; ?>>Plumbing</option>
                    <option value="Renovations" <?php if ($specialty == 'Renovations') echo 'selected'; ?>>Renovations</option>
                    <option value="Electrical" <?php if ($specialty == 'Electrical') echo 'selected'; ?>>Electrical</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="inputLocation3" class="form-label">Location</label>
                  <select class="form-control" id="inputLocation3" name="location" required>
                    <option value="" disabled>Select Location</option>
                    <?php
                    $locations = ['Titiwangsa', 'Setapak', 'Sentul', 'Wangsa Maju', 'Chow Kit', 'Bukit Bintang', 'Kampung Baru', 'Dang Wangi', 'Pudu', 'Cheras'];
                    foreach ($locations as $loc) {
                      echo "<option value='$loc' " . ($location == $loc ? 'selected' : '') . ">$loc</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="inputPrice3" class="form-label">Basic Price</label>
                  <input type="text" step="0.2" class="form-control" id="inputPrice3" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
                </div>
                <div class="mb-3">
                  <label for="inputRating3" class="form-label">Rating</label>
                  <input type="number" step="0.1" class="form-control" id="inputRating3" name="rating" value="<?php echo htmlspecialchars($rating); ?>" required>
                </div>

                <!-- Display Current Profile Picture -->
                <div class="mb-3">
                  <label class="form-label">Current Profile Picture:</label><br>
                  <?php 
                  $image_path = "backend/uploads/" . $tech_profile_picture;
                  if (!empty($tech_profile_picture) && file_exists($image_path)) { ?>
                    <img src="<?php echo htmlspecialchars($image_path); ?>" 
                         alt="Technician Profile Picture" width="100" height="100" 
                         style="border-radius: 50%;"><br>
                  <?php } else { ?>
                    <img src="backend/upload/default.jpg" 
                         alt="Default Profile Picture" width="100" height="100" 
                         style="border-radius: 50%;"><br>
                    <p>No profile picture available. Showing default image.</p>
                  <?php } ?>
                </div>

                <!-- Optional File Upload -->
                <div class="mb-3">
                  <label for="inputImage3" class="form-label">Upload New Profile Picture (Optional)</label>
                  <input type="file" class="form-control" id="inputImage3" name="image">
                </div>

                <button type="submit" name="edit_technician" class="btn btn-success">Edit Technician</button>
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
  <!--end footer-->

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
        </div>
      </div>
    </div>
  </div>
  <!--end switcher-->

  <!--bootstrap js-->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="assets/plugins/metismenu/metisMenu.min.js"></script>
  <script src="assets/plugins/form-repeater/repeater.js"></script>
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>