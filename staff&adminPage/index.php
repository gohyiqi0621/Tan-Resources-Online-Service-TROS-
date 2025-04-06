<?php
session_start();
include 'backend/database.php'; // Ensure this file establishes a database connection

$name = 'User'; // Default name
$profile_picture = 'user-profile-default.webp'; // Default profile picture
$total_sales = 0;
$total_orders = 0;
$total_customers = 0;
$this_month_customers = 0;
$last_month_customers = 0;
$customer_growth = 0;
$total_technicians = 0;

$service_counts = [
    'plumbing' => 0,
    'renovation' => 0,
    'electrical' => 0
];

$highest_order_month = "N/A";
$highest_order_count = 0;

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

    // Fetch total technicians
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_technicians FROM technicians");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_technicians = $row['total_technicians'] ?? 0;

    // Total sales for completed transactions
    $stmt = $conn->prepare("SELECT SUM(amount) AS total_sales FROM payments WHERE payment_status = 'paid' AND status = 'Completed'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_sales = $row['total_sales'] ?? 0;

    // Total number of orders
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_orders FROM payments");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_orders = $row['total_orders'] ?? 0;

    // Total customers
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_customers FROM users WHERE role = 'customer'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_customers = $row['total_customers'] ?? 0;

    // Customers this month
    $stmt = $conn->prepare("SELECT COUNT(*) AS this_month_customers FROM users WHERE role = 'customer' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $this_month_customers = $row['this_month_customers'] ?? 0;

    // Customers last month
    $stmt = $conn->prepare("SELECT COUNT(*) AS last_month_customers FROM users WHERE role = 'customer' AND MONTH(created_at) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURDATE())");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $last_month_customers = $row['last_month_customers'] ?? 0;

    // Calculate growth percentage
    if ($last_month_customers > 0) {
        $customer_growth = (($this_month_customers - $last_month_customers) / $last_month_customers) * 100;
    } elseif ($this_month_customers > 0) {
        $customer_growth = 100;
    } else {
        $customer_growth = 0;
    }

    // Fetch count of each service type
    $stmt = $conn->prepare("SELECT service_type, COUNT(*) AS count FROM payments WHERE status = 'Completed' GROUP BY service_type");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $service_counts[$row['service_type']] = (int)$row['count'];
    }

    // Highest order month
    $stmt = $conn->prepare("
        SELECT MONTH(booking_date) AS month_number, COUNT(*) AS order_count
        FROM payments 
        WHERE status = 'Completed'
        GROUP BY month_number
        ORDER BY order_count DESC
        LIMIT 1
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $highest_order_month = date("F", mktime(0, 0, 0, $row['month_number'], 1));
        $highest_order_count = $row['order_count'];
    }

    // Fetch the 5 most recent orders (including technician's profile picture)
    $stmt = $conn->prepare("
        SELECT 
            p.id, 
            p.amount, 
            p.status, 
            p.technician_id, 
            p.user_id, 
            t.name AS technician_name, 
            t.rating, 
            t.image AS technician_image, 
            u.name AS customer_name 
        FROM payments p
        LEFT JOIN technicians t ON p.technician_id = t.id
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT 5
    ");
    $stmt->execute();
    $result = $stmt->get_result();
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

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
					<div class="breadcrumb-title pe-3">Dashboard</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Analysis</li>
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
     
        <div class="row">
          <div class="col-xxl-8 d-flex align-items-stretch">
            <div class="card w-100 overflow-hidden rounded-4">
              <div class="card-body position-relative p-4">
                <div class="row">
                  <div class="col-12 col-sm-7">
                    <div class="d-flex align-items-center gap-3 mb-5">
                      <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="rounded-circle bg-grd-info p-1"  width="60" height="60" alt="user">
                      <div class="">
                        <p class="mb-0 fw-semibold">Welcome back</p>
                        <h4 class="fw-semibold mb-0 fs-4 mb-0"><?php echo htmlspecialchars($name); ?></h4>
                      </div>
                    </div>
                    <div class="d-flex align-items-center gap-5">
                      <div class="">
                          <h4 class="mb-1 fw-semibold d-flex align-content-center">
                              RM<?php echo number_format($total_sales, 2); ?>
                              <i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                          </h4>
                        <p class="mb-3">Total Sales</p>
                        <div class="progress mb-0" style="height:5px;">
                          <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                      <div class="vr"></div>
                      <div class="">
                      <h4 class="mb-1 fw-semibold d-flex align-content-center">
                          <?php echo $total_orders; ?> 
                      </h4>
                        <p class="mb-3">Total Order</p>
                        <div class="progress mb-0" style="height:5px;">
                          <div class="progress-bar bg-grd-danger" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-5">
                    <div class="welcome-back-img pt-4">
                       <img src="assets/images/gallery/welcome-back-3.png" height="180" alt="">
                    </div>
                  </div>
                </div><!--end row-->
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-xxl-2 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
              <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-1">
                  <div class="">
                    <h5 class="mb-0"><?php echo number_format($total_customers); ?></h5>
                    <p class="mb-0">Customer</p>
                  </div>
                  <div class="dropdown">
                   
                  </div>
                </div>
                <div class="chart-container2">
                  <div id="chart1"></div>
                </div>
                <div class="text-center">
                <p class="mb-0 font-12">
                <?php 
                  echo number_format($this_month_customers - $last_month_customers) . " users increased from last month";
                ?>
                </p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-xxl-2 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
              <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="">
                    <h5 class="mb-0"><?php echo number_format($total_technicians); ?></h5>
                    <p class="mb-0">Total Technicians</p>
                  </div>
                  <div class="dropdown">
                    
                  </div>
                </div>
                <div class="chart-container2">
                  <div id="numberTechnicians"></div>
                </div>
                <div class="text-center">
                  <p class="mb-0 font-12"><span class="text-success me-1">100.00%</span> Available Technician</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
              <div class="card-body">
                <div class="text-center">
                  <h6 class="mb-0">Monthly Order</h6>
                </div>
                <div class="mt-4" id="chart5"></div>
                <p>Highest Order Month</p>
                <div class="d-flex align-items-center gap-3 mt-4">
                    <div class="">
                      <h1 class="mb-0 text-primary"><?php echo $highest_order_count; ?></h1>
                    </div>
                    <div class="d-flex align-items-center align-self-end">
                        <p class="mb-0 text-success"><?php echo $highest_order_month; ?></p>
                    </div> 
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
              <div class="card-body">
                <div class="d-flex flex-column gap-3">
                  <div class="d-flex align-items-start justify-content-between">
                    <div class="">
                      <h5 class="mb-0">Service Type</h5>
                    </div>
                  </div>
                  <div class="position-relative">
                    <div class="piechart-legend">
                      <h2 class="mb-1"><?php echo $total_orders; ?> </h2>
                      <h6 class="mb-0">Total Counts</h6>
                    </div>
                    <div id="chart6"></div>
                  </div>
                  <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between" style="margin-top:25px;">
                      <p class="mb-0 d-flex align-items-center gap-2 w-25">
                        <span class="material-icons-outlined fs-6" style="color: #ff6a00;">build</span>Plumbing
                      </p>
                      <div class="">
                        <p class="mb-0" id="plumbingPercentage">0%</p>
                      </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                      <p class="mb-0 d-flex align-items-center gap-2 w-25">
                        <span class="material-icons-outlined fs-6" style="color: #98ec2d;">construction</span>Renovation
                      </p>
                      <div class="">
                        <p class="mb-0" id="renovationPercentage">0%</p>
                      </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                      <p class="mb-0 d-flex align-items-center gap-2 w-25">
                        <span class="material-icons-outlined fs-6" style="color: #3494e6;">bolt</span>Electrical
                      </p>
                      <div class="">
                        <p class="mb-0" id="electricalPercentage">0%</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
              <div class="card-body">
                <div class="d-flex flex-column gap-3">
                  <div class="d-flex align-items-start justify-content-between">
                    <div class="">
                      <h5 class="mb-0">Average Rating</h5>
                    </div>
                  </div>
                  <div class="position-relative">
                    <div id="technicianChart"></div>
                  </div>
                  <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between">
                      <p class="mb-0 d-flex align-items-center gap-2 w-25">
                        <span class="material-icons-outlined fs-6" style="color: #ff6a00;">build</span>Plumbing
                      </p>
                      <div class="">
                        <p class="mb-0" id="plumbingRating">0.0</p>
                      </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                      <p class="mb-0 d-flex align-items-center gap-2 w-25">
                        <span class="material-icons-outlined fs-6" style="color: #98ec2d;">construction</span>Renovation
                      </p>
                      <div class="">
                        <p class="mb-0" id="renovationsRating">0.0</p>
                      </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                      <p class="mb-0 d-flex align-items-center gap-2 w-25">
                        <span class="material-icons-outlined fs-6" style="color: #3494e6;">bolt</span>Electrical
                      </p>
                      <div class="">
                        <p class="mb-0" id="electricalRating">0.0</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-xl-6 col-xxl-4 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
              <div class="card-header border-0 p-3 border-bottom">
                <div class="d-flex align-items-start justify-content-between">
                  <div class="">
                    <h5 class="mb-0">New Users</h5>
                  </div>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="user-list p-3">
                  <div class="d-flex flex-column gap-3">
                    <div id="customer-list" class="d-flex flex-column gap-3"></div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <div class="col-lg-12 col-xxl-8 d-flex align-items-stretch">
            <div class="card w-100 rounded-4">
              <div class="card-body">
               <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="">
                    <h5 class="mb-0">Recent Orders</h5>
                  </div>

                </div>
                <div class="order-search position-relative my-3">
                  <input id="searchInput" class="form-control rounded-5 px-5" type="text" placeholder="Search">
                  <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
              </div>
                 <div class="table-responsive">
                 <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Technician Name</th>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                  <?php 
                  // Fetch the 5 most recent orders
                  $stmt = $conn->prepare("
                      SELECT 
                          p.id, 
                          p.amount, 
                          p.status, 
                          p.technician_id, 
                          p.user_id, 
                          t.name AS technician_name, 
                          t.rating, 
                          t.image AS technician_image, 
                          u.name AS customer_name 
                      FROM payments p
                      LEFT JOIN technicians t ON p.technician_id = t.id
                      LEFT JOIN users u ON p.user_id = u.id
                      ORDER BY p.created_at DESC
                      LIMIT 5
                  ");
                  $stmt->execute();
                  $result = $stmt->get_result();

                  while ($row = $result->fetch_assoc()) :
                  ?>
                      <tr>
                          <td>
                              <div class="d-flex align-items-center gap-3">
                                  <div>
                                      <?php if (!empty($row['technician_image'])): ?>
                                          <img src="backend/uploads/<?= htmlspecialchars($row['technician_image']) ?>" 
                                              class="rounded-circle" width="50" height="50" alt="Technician">
                                      <?php else: ?>
                                          <img src="user-profile-default.webp" class="rounded-circle" width="50" height="50" alt="No Image">
                                      <?php endif; ?>
                                  </div>
                                  <p class="mb-0"><?= htmlspecialchars($row['technician_name'] ?? 'Unknown'); ?></p>
                              </div>
                          </td>
                          <td>RM <?= number_format($row['amount'], 2); ?></td>
                          <td><?= htmlspecialchars($row['customer_name'] ?? 'Unknown'); ?></td>
                          <td>
                              <p class="dash-lable mb-0 
                                  <?= ($row['status'] === 'Completed') ? 'bg-success text-success' : 'bg-warning text-warning'; ?> 
                                  bg-opacity-10 rounded-2">
                                  <?= htmlspecialchars($row['status']); ?>
                              </p>
                          </td>
                          <td>
                              <div class="d-flex align-items-center gap-1">
                                  <p class="mb-0"><?= number_format($row['rating'], 1); ?></p>
                                  <i class="material-icons-outlined text-warning fs-6">star</i>
                              </div>
                          </td>
                      </tr>
                  <?php endwhile; ?>
              </tbody>


                    </table>
                 </div>
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

  <!--start cart-->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart">
    <div class="offcanvas-header border-bottom h-70">
      <h5 class="mb-0" id="offcanvasRightLabel">8 New Orders</h5>
      <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
        <i class="material-icons-outlined">close</i>
      </a>
    </div>
    <div class="offcanvas-body p-0">
      <div class="order-list">
        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/01.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">White Men Shoes</h5>
            <p class="mb-0 order-price">$289</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>

        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/02.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">Red Airpods</h5>
            <p class="mb-0 order-price">$149</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>

        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/03.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">Men Polo Tshirt</h5>
            <p class="mb-0 order-price">$139</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>

        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/04.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">Blue Jeans Casual</h5>
            <p class="mb-0 order-price">$485</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>

        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/05.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">Fancy Shirts</h5>
            <p class="mb-0 order-price">$758</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>

        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/06.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">Home Sofa Set </h5>
            <p class="mb-0 order-price">$546</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>

        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/07.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">Black iPhone</h5>
            <p class="mb-0 order-price">$1049</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>

        <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
          <div class="order-img">
            <img src="assets/images/orders/08.png" class="img-fluid rounded-3" width="75" alt="">
          </div>
          <div class="order-info flex-grow-1">
            <h5 class="mb-1 order-title">Goldan Watch</h5>
            <p class="mb-0 order-price">$689</p>
          </div>
          <div class="d-flex">
            <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
            <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
          </div>
        </div>
      </div>
    </div>
    <div class="offcanvas-footer h-70 p-3 border-top">
      <div class="d-grid">
        <button type="button" class="btn btn-grd btn-grd-primary" data-bs-dismiss="offcanvas">View Products</button>
      </div>
    </div>
  </div>
  <!--end cart-->



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
  <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/plugins/peity/jquery.peity.min.js"></script>
  <script>
    $(".data-attributes span").peity("donut")
  </script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/dashboard1.js"></script>
  <script>
	   new PerfectScrollbar(".user-list")
  </script>

  <script>
      // Get the customer growth percentage from PHP
      var customerGrowth = <?php echo round($customer_growth, 2); ?>; 

      // Ensure it doesn't go below 0%
      if (customerGrowth < 0) {
          customerGrowth = 0;
      }

      var chart = new ApexCharts(document.querySelector("#chart1"), options);
      chart.render();

  </script>

  <!--Chart 5-->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("backend/graph/fetch_order.php") // Adjust the path if necessary
            .then(response => response.json())
            .then(data => {
                var options = {
                    series: [{
                        name: "Orders",
                        data: data.orders
                    }],
                    chart: {
                        foreColor: "#9ba7b2",
                        height: 280,
                        type: 'bar',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: { width: 1, curve: 'smooth' },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 4,
                            columnWidth: '45%'
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            gradientToColors: ['#009efd'],
                            shadeIntensity: 1,
                            type: 'vertical',
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    colors: ["#2af598"],
                    grid: {
                        show: true,
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                    },
                    xaxis: { categories: data.months },
                    yaxis: {
                        min: 0,
                        max: 30,
                        tickAmount: 3, // Divides into intervals of 10
                    },
                    tooltip: { theme: "dark", marker: { show: false } },
                };

                var chart = new ApexCharts(document.querySelector("#chart5"), options);
                chart.render();
            })
            .catch(error => console.error("Error fetching data:", error));
    });
  </script>

  <!--Chart 6-->
  <script>
          document.addEventListener("DOMContentLoaded", function () {
          fetch('/TROS%20NEW/staff%26adminPage/backend/graph/get_service_counts.php')
              .then(response => {
                  if (!response.ok) throw new Error(`❌ API Request Failed: ${response.status}`);
                  return response.json();
              })
              .then(data => {
                  console.log("Fetched Data:", data); // Debugging

                  if (!data || typeof data.plumbing === "undefined") {
                      throw new Error("❌ Invalid API Response");
                  }

                  var options = {
                      series: [data.plumbing, data.renovation, data.electrical], // Use fetched counts
                      chart: { height: 290, type: 'donut' },
                      legend: { position: 'bottom', show: false },
                      fill: { type: 'gradient' },
                      colors: ["#ff6a00", "#98ec2d", "#3494e6"],
                      dataLabels: { enabled: false },
                      plotOptions: { pie: { donut: { size: "85%" } } }
                  };

                  var chart = new ApexCharts(document.querySelector("#chart6"), options);
                  chart.render();
              })
              .catch(error => console.error("🚨 Error fetching data:", error));
      });

      document.addEventListener("DOMContentLoaded", function () {
        fetch('backend/graph/fetch_feedback_stats.php')
          .then(response => response.json())
          .then(data => {
            document.getElementById("plumbingPercentage").innerText = data.plumbing + "%";
            document.getElementById("renovationPercentage").innerText = data.renovation + "%";
            document.getElementById("electricalPercentage").innerText = data.electrical + "%";
          })
          .catch(error => console.error("Error fetching feedback stats:", error));
      });

  </script>

  <!--Technician Rating Chart-->
  <script>     
    document.addEventListener("DOMContentLoaded", function () {
        fetch("backend/graph/get_top_technicians.php")
            .then(response => response.json())
            .then(data => {
                console.log("Received Data:", data); // Debugging log

                // Ensure all three categories are present
                const specialties = ["Electrical", "Renovations", "Plumbing"]; 
                const topTechnicians = specialties.map(specialty => {
                    const topTech = data
                        .filter(row => row.specialty.toLowerCase() === specialty.toLowerCase())
                        .sort((a, b) => b.avg_rating - a.avg_rating)[0];

                    return topTech ? {
                        name: topTech.technician_name,
                        rating: parseFloat(topTech.avg_rating).toFixed(2),
                        specialty: specialty
                    } : { name: "No Technician", rating: 0, specialty: specialty }; 
                });

                // Prepare data for ApexCharts
                var options = {
                    series: [{
                        name: "Average Rating",
                        data: topTechnicians.map(tech => tech.rating)
                    }],
                    chart: {
                        foreColor: "#9ba7b2",
                        height: 280,
                        type: 'bar',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    dataLabels: { 
                        enabled: true,
                        formatter: function (val, opts) {
                            return topTechnicians[opts.dataPointIndex].name; // Show technician name in the bar
                        },
                        style: {
                            colors: ["#fff"]
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 4,
                            columnWidth: '45%'
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            gradientToColors: ['#009efd'],
                            shadeIntensity: 1,
                            type: 'vertical',
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    colors: ["#2af598"],
                    grid: {
                        show: true,
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                    },
                    xaxis: { 
                        categories: specialties,
                        title: { text: "" } // Removed "Specialty"
                    },
                    yaxis: {
                        min: 0,
                        max: 5,
                        tickAmount: 5,
                        title: { text: "Average Rating (1-5)" }
                    },
                    tooltip: { theme: "dark", marker: { show: false } },
                };

                var chart = new ApexCharts(document.querySelector("#technicianChart"), options);
                chart.render();
            })
            .catch(error => console.error("Error fetching data:", error));
    });


    document.addEventListener("DOMContentLoaded", function () {
        fetch("backend/graph/get_average_ratings.php")
            .then(response => response.json())
            .then(data => {
                console.log("Average Ratings Data:", data); // Debugging

                // Define IDs for updating UI
                const ratingElements = {
                    Plumbing: document.getElementById("plumbingRating"),
                    Renovations: document.getElementById("renovationsRating"),
                    Electrical: document.getElementById("electricalRating"),
                };

                // Update the UI dynamically
                Object.keys(ratingElements).forEach(specialty => {
                    if (data[specialty]) {
                        ratingElements[specialty].textContent = data[specialty].avg_rating;
                    } else {
                        ratingElements[specialty].textContent = "N/A"; // If no data, show N/A
                    }
                });
            })
            .catch(error => console.error("Error fetching average ratings:", error));
    });

</script>

<!--search customer-->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.getElementById("ordersTableBody");
    let defaultOrdersHTML = tableBody.innerHTML; // Store initial table data

    searchInput.addEventListener("keyup", function () {
        let query = this.value.trim();

        if (query === "") {
            tableBody.innerHTML = defaultOrdersHTML; // Restore original 5 recent orders
            return;
        }

        fetch("backend/search_orders.php?query=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = ""; // Clear previous data

                if (data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='5' class='text-center'>No results found</td></tr>";
                    return;
                }

                data.forEach(row => {
                    let technicianImage = row.technician_image ? `backend/uploads/${row.technician_image}` : "user-profile-default.webp";
                    let statusClass = row.status === "Completed" ? "bg-success text-success" : "bg-warning text-warning";

                    let rowHTML = `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <img src="${technicianImage}" class="rounded-circle" width="50" height="50" alt="Technician">
                                    </div>
                                    <p class="mb-0">${row.technician_name ?? "Unknown"}</p>
                                </div>
                            </td>
                            <td>RM ${parseFloat(row.amount).toFixed(2)}</td>
                            <td>${row.customer_name ?? "Unknown"}</td>
                            <td>
                                <p class="dash-lable mb-0 ${statusClass} bg-opacity-10 rounded-2">${row.status}</p>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <p class="mb-0">${parseFloat(row.rating).toFixed(1)}</p>
                                    <i class="material-icons-outlined text-warning fs-6">star</i>
                                </div>
                            </td>
                        </tr>
                    `;

                    tableBody.innerHTML += rowHTML;
                });
            })
            .catch(error => console.error("Error fetching data:", error));
    });
});

</script>

<!--GET USER-->

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("backend/get_recent_users.php")
        .then(response => response.json())
        .then(data => {
            const customerList = document.getElementById("customer-list");
            customerList.innerHTML = ""; // Clear existing content
            
            data.forEach(customer => {
                const profilePic = customer.profile_picture ? `../customer%20page/profile/uploads/${customer.profile_picture}` : `user-profile-default.webp`;

                const customerHTML = `
                    <div class="d-flex align-items-center gap-3">
                        <img src="${profilePic}" width="45" height="45" class="rounded-circle" alt="">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">${customer.name}</h6>
                            <p class="mb-0">${customer.email}</p>
                        </div>
                    </div>
                `;
                customerList.innerHTML += customerHTML;
            });
        })
        .catch(error => console.error("Error fetching customers:", error));
});
</script>

<!--TECHNICIANS-->

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("backend/graph/get_active_technicians.php")
        .then(response => response.json())
        .then(data => {
            console.log("Fetched Data:", data);

            if (!data || data.length === 0) {
                console.error("No technician data received.");
                document.querySelector("#numberTechnicians").innerHTML = "<p style='color:white; text-align:center;'>No Data Available</p>";
                return;
            }

            const categories = data.map(item => item.specialty);
            const values = data.map(item => parseInt(item.count, 10));

            var options = {
                series: values,
                chart: {
                    height: 150, 
                    width: 150,  
                    type: 'pie'
                },
                colors: ["#ff6a00", "#98ec2d", "#3494e6"],
                dataLabels: {
                    enabled: false // No percentage inside chart
                },
                legend: {
                    show: false // No color legend
                },
                tooltip: {
                    enabled: true,
                    theme: "dark",
                    y: {
                        formatter: function (value, { seriesIndex }) {
                            return `${categories[seriesIndex]}: ${value}`; // Show "Plumbing: 46"
                        }
                    }
                }
            };

            document.querySelector("#numberTechnicians").innerHTML = ""; // Clear old chart
            var chart = new ApexCharts(document.querySelector("#numberTechnicians"), options);
            chart.render();
        })
        .catch(error => console.error("Error fetching technician data:", error));
});


</script>

<script>
        function toggleSearch() {
            let searchInput = document.getElementById("liveSearch");
            if (searchInput.style.display === "none" || searchInput.style.display === "") {
                searchInput.style.display = "block";
                searchInput.focus();
            } else {
                searchInput.style.display = "none";
                clearHighlights();
            }
        }

        function performSearch() {
            let query = document.getElementById("liveSearch").value.toLowerCase();
            let elements = document.querySelectorAll("body *:not(script):not(style)");

            clearHighlights();

            if (query !== "") {
                elements.forEach(el => {
                    if (el.textContent.toLowerCase().includes(query)) {
                        el.classList.add("highlight");
                        el.scrollIntoView({ behavior: "smooth", block: "center" });
                    }
                });
            }
        }

        function clearHighlights() {
            document.querySelectorAll(".highlight").forEach(el => {
                el.classList.remove("highlight");
            });
        }

</script>

<script>
      function performSearch() {
        let query = document.getElementById("liveSearch").value.toLowerCase();
        let elements = document.querySelectorAll("p, h1, h2, h3, h4, h5, h6, li, span, div"); // Select only readable text elements

        if (query === "") return; // Do nothing if input is empty

        for (let el of elements) {
            if (el.textContent.toLowerCase().includes(query)) {
                el.scrollIntoView({ behavior: "smooth", block: "center" }); // Scroll to the first match
                break; // Stop after finding the first match
            }
        }
    }

</script>

  

</body>

</html>