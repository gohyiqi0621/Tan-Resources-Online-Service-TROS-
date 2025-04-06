<?php 
session_start(); // Start session first
include 'backend/database.php'; // Ensure database connection

// Default user details
$name = 'User';
$profile_picture = 'user-profile-default.webp';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user_result = $stmt->get_result(); // Use a different variable

    if ($user = $user_result->fetch_assoc()) {
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

  <style>
    .table {
          width: 100%; /* Ensure the table takes full width */
          table-layout: fixed; /* Distribute column widths properly */
      }

      th, td {
          padding: 12px 15px;
          text-align: left;
          white-space: nowrap; /* Prevents text wrapping */
      }


      /* Reduce width of the ID column */
      th:nth-child(1), td:nth-child(1) {
          width: 50px; /* Adjust as needed */
          text-align: center;
      }

      /* Increase width of Profile Picture & Name column */
      th:nth-child(2), td:nth-child(2) {
          width: 150px; /* Adjust as needed */
      }

      th:nth-child(7), td:nth-child(7) {
          width: 100px; /* Adjust as needed */
      }

      /* Adjust profile picture size */
      .customer-pic {
          width: 80px; /* More space for the profile picture */
          text-align: center;
      }

      .customer-pic img {
          width: 60px; /* Bigger profile image */
          height: 60px;
          border-radius: 50%;
      }

      /* Align Edit & Delete buttons to the right */
      .actions {
          text-align: right;
          width: 160px; /* Ensure enough space */
      }

      .actions button {
          margin-left: 5px; /* Add spacing between buttons */
      }

      .profile-img {
        width: 50px;
        height: 50px;
        border-radius: 50%; /* Makes it circular */
        object-fit: cover;  /* Ensures the image fills the circle */
        margin-right:20px; /* Adds spacing between image and name */
    }



  </style>

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
								<li class="breadcrumb-item active" aria-current="page">Technicians</li>
							</ol>
						</nav>
					</div>
					
				</div>
				<!--end breadcrumb-->

        <div class="row g-3">
          <div class="col-auto">
          <div class="position-relative">
            <input id="searchTechnicians" class="form-control px-5" type="search" placeholder="Search Technicians" onkeyup="searchTechnicians()">
            <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
        </div>
          </div>
          <div class="col-auto flex-grow-1 overflow-auto">
            <div class="btn-group position-static">
            <!-- Specialty Filter -->
            <div class="btn-group position-static">
                <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown">
                    Specialty
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('specialty', 'All')">All</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('specialty', 'Plumbing')">Plumbing</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('specialty', 'Electrical')">Electrical</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('specialty', 'Renovations')">Renovations</a></li>
                </ul>
            </div>

            <!-- Location Filter -->
            <div class="btn-group position-static">
                <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown">
                    Location
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('location', 'All')">All</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('location', 'Titiwangsa')">Titiwangsa</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('location', 'Setapak')">Setapak</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('location', 'Bukit Bintang')">Bukit Bintang</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('location', 'Kampung Baru')">Kampung Baru</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="filterTechnicians('location', 'Cheras')">Cheras</a></li>
                </ul>
            </div>
              
            </div>  
          </div>
          <div class="col-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
            <button class="btn btn-filter px-4" onclick="exportTableToPDF()">
              <i class="bi bi-box-arrow-right me-2"></i>Export PDF
            </button>


               <button class="btn btn-primary px-4" onclick="window.location.href='add-technician.php'">
                  <i class="bi bi-plus-lg me-2"></i>Add Technicians
              </button>
            </div>
          </div>
        </div><!--end row-->

        <div class="card mt-4">
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table align-middle">
                      <thead class="table-light">
                          <tr>
                              <th>ID</th>
                              <th>Technician</th>
                              <th>Phone</th>
                              <th>Specialty</th>
                              <th>Location</th>
                              <th>Price</th>
                              <th>Rating</th>
                              <th>Availability</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody id="technicianTableBody">
                          <?php include 'backend/fetch_technicians.php'; ?>
                      </tbody>
                  </table>
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
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
document.addEventListener("DOMContentLoaded", function () {
    searchTechnicians();
});

function searchTechnicians() {
    let searchValue = document.getElementById("searchTechnicians") ? document.getElementById("searchTechnicians").value : '';
    let specialtyFilter = sessionStorage.getItem("specialty") || '';
    let locationFilter = sessionStorage.getItem("location") || '';

    // Convert "All" back to empty string (to reset the filter)
    specialtyFilter = specialtyFilter === "All" ? "" : specialtyFilter;
    locationFilter = locationFilter === "All" ? "" : locationFilter;

    // Debugging: Check values in console
    console.log("Search:", searchValue);
    console.log("Specialty:", specialtyFilter);
    console.log("Location:", locationFilter);

    // Fetch filtered technicians
    fetch(`backend/fetch_technicians.php?search=${encodeURIComponent(searchValue)}&specialty=${encodeURIComponent(specialtyFilter)}&location=${encodeURIComponent(locationFilter)}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById("technicianTableBody").innerHTML = data;
        })
        .catch(error => console.error("Error fetching technicians:", error));
}

// Function to apply filters
function filterTechnicians(filterType, value) {
    if (filterType === "specialty") {
        sessionStorage.setItem("specialty", value);
    } else if (filterType === "location") {
        sessionStorage.setItem("location", value);
    }

    searchTechnicians(); // Refresh table
}

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    window.jsPDF = window.jspdf.jsPDF;
});

function exportTableToPDF() {
    const { jsPDF } = window.jspdf;
    let doc = new jsPDF();

    // Add Title
    doc.text("Filtered Technician List", 14, 15);

    // Select table and extract only the visible rows
    let table = document.getElementById("technicianTableBody");
    
    if (!table || table.rows.length === 0) {
        alert("No data available to export.");
        return;
    }

    let rows = [];
    let headers = ["ID", "Name", "Phone", "Specialty", "Location", "Price", "Rating", "Status"];

    for (let row of table.rows) {
        let rowData = [];
        for (let cell of row.cells) {
            rowData.push(cell.innerText.trim());
        }
        rows.push(rowData);
    }

    // Generate AutoTable in PDF
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 20,          
        theme: "grid",
        styles: { fontSize: 10 },
        headStyles: { fillColor: [0, 0, 255] },
    });

    // Save PDF
    doc.save("technician_list.pdf");
}
</script>


<!-- Load jsPDF and autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>





</body>

</html>