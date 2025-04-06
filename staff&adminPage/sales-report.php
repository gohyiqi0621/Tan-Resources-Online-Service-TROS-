<?php
session_start();
// Include database connection
include 'backend/database.php';

// Get filter values
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$status = isset($_GET['status']) ? $_GET['status'] : '';
$service_type = isset($_GET['service_type']) ? $_GET['service_type'] : '';

// Build SQL query
$sql = "SELECT * FROM payments WHERE 1=1";

if (!empty($month)) {
    $sql .= " AND MONTH(booking_date) = '$month' AND YEAR(booking_date) = '$year'";
}

if (!empty($status)) {
    $sql .= " AND status = '$status'";
}

if (!empty($service_type)) {
    $sql .= " AND service_type = '$service_type'";
}

$sql .= " ORDER BY booking_date DESC";

$result = $conn->query($sql);

// Initialize total sales variables
$total_sales = 0;
$total_transactions = 0;
$monthly_sales = 0;
$monthly_completed_transactions = 0;

while ($row = $result->fetch_assoc()) {
    if ($row['status'] == 'Completed') {
        $total_sales += $row['amount'];
        $total_transactions++;

        if (!empty($month) && date('m', strtotime($row['booking_date'])) == $month) {
            $monthly_sales += $row['amount'];
            $monthly_completed_transactions++;
        }
    }
}

// Reset MySQL result pointer
$result->data_seek(0);

// Determine values to display
$display_sales = !empty($month) ? $monthly_sales : $total_sales;
$display_transactions = !empty($month) ? $monthly_completed_transactions : $total_transactions;

// Generate months dropdown
$months = [
    "1" => "January", "2" => "February", "3" => "March", "4" => "April",
    "5" => "May", "6" => "June", "7" => "July", "8" => "August",
    "9" => "September", "10" => "October", "11" => "November", "12" => "December"
];

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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Chart.js for Charts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
  /* General Reset and Base Styles */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Noto Sans', sans-serif;
  }

  body {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    min-height: 100vh;
    padding: 20px;
    color: #ffffff; /* Default text color to white */
  }

  /* Enhanced Filter Row */
  .filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    margin: 20px;
    backdrop-filter: blur(10px);
  }

  .filter-col {
    flex: 1 1 220px;
    min-width: 0;
  }

  .filter-col label {
    color: #ffffff; /* Filter labels to white */
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    font-size: 14px;
  }

  .filter-col select {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #2d3748;
    color: #ffffff; /* Dropdown text to white */
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .filter-col select:hover {
    background: #3b4a6b;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .filter-col select:focus {
    outline: none;
    background: #4a557a;
    box-shadow: 0 4px 12px rgba(74, 85, 122, 0.5);
  }

  /* Card Styling */
  .card {
    background: #1e1e2d;
    border: none;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    padding: 25px;
    margin: 0 20px 20px;
  }

  .card-title {
    color: #ffffff; /* Card title to white */
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
  }

  /* Table Styling */
  .dark-table {
    width: 100%;
    border-collapse: collapse;
    background: #252537;
    color: #ffffff; /* Table text to white */
    border-radius: 10px;
    overflow: hidden;
    font-size: 14px;
  }

  .dark-table th {
    background: #2a2a3c;
    color: #ffffff; /* Table header text to white */
    padding: 16px;
    text-align: left;
    border-bottom: 2px solid #3d3d5c;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .dark-table td {
    padding: 16px;
    border-bottom: 1px solid #3d3d5c;
  }

  .dark-table tr:nth-child(even) {
    background: #2d2d3f;
  }

  .dark-table tr:hover {
    background: #37374d;
    transition: background 0.3s ease;
  }

  /* Status Colors (unchanged to preserve specific colors) */
  td.status-canceled { color: #ff4c4c !important; font-weight: bold; }
  td.status-completed { color: #3adb76 !important; font-weight: bold; }
  td.status-pending { color: #ffcc00 !important; font-weight: bold; }

  /* Summary Section */
  .summary-section {
    margin-top: 25px;
    padding: 20px;
    background: #2d2d3f;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  }

  .summary-section h4 {
    color: #ffffff; /* Summary heading to white */
    font-size: 22px;
    margin-bottom: 12px;
  }

  .summary-section h5 {
    color: #ffffff; /* Summary subheading to white */
    font-size: 18px;
  }

  .text-success { color: #3adb76 !important; font-weight: bold; }
  .text-info { color: #00c4ff !important; font-weight: bold; }

  /* Chart Container */
  .chart-container {
    margin-top: 30px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
  }

  .chart-box {
    flex: 1 1 45%;
    min-width: 300px;
    background: #252537;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .chart-box h5 {
    color: #ffffff; /* Chart titles to white */
    font-size: 18px;
    margin-bottom: 15px;
    text-align: center;
  }

  /* Ensure both charts are the same size */
  .chart-box canvas {
    width: 300px !important;
    height: 300px !important;
    max-width: 100%;
  }

  /* Button Styling */
  .btn-export {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    padding: 10px 20px;
    color: #ffffff; /* Button text to white */
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-export:hover {
    background: linear-gradient(135deg, #764ba2, #667eea);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .filter-col { flex: 1 1 100%; }
    .filter-row { padding: 15px; }
    .dark-table th, .dark-table td { padding: 12px; font-size: 12px; }
    .chart-box { flex: 1 1 100%; }
    .chart-box canvas {
      width: 250px !important;
      height: 250px !important;
    }
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
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <!-- Enhanced Filter Section -->
      <div class="filter-row">
        <div class="filter-col">
          <label for="yearSelect">Year</label>
          <select id="yearSelect" class="form-control">
            <option value="">All Years</option>
            <option value="2025" selected>2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
          </select>
        </div>
        <div class="filter-col">
          <label for="monthSelect">Month</label>
          <select id="monthSelect" class="form-control">
            <option value="">All Months</option>
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
          </select>
        </div>
        <div class="filter-col">
          <label for="statusSelect">Status</label>
          <select id="statusSelect" class="form-control">
            <option value="">All</option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
          </select>
        </div>
        <div class="filter-col">
          <label for="serviceTypeSelect">Service Type</label>
          <select id="serviceTypeSelect" class="form-control">
            <option value="">All</option>
            <option value="Plumbing">Plumbing</option>
            <option value="Renovation">Renovation</option>
            <option value="Electrical">Electrical</option>
          </select>
        </div>
      </div>

      <!-- Main Content Card -->
      <div class="card mt-4">
        <div class="card-body">
          <h3 class="card-title">Sales Overview</h3>
          <button id="exportPdf" class="btn btn-export mb-3">Export to PDF</button>
          <div class="table-responsive white-space-nowrap" style="overflow-x: auto; width: 100%;">
            <table id="salesTable" class="dark-table">
              <thead>
                <tr>
                  <th>Transaction ID</th>
                  <th>Amount</th>
                  <th>Booking Date</th>
                  <th>Service Type</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
            </table>
          </div>

          <!-- Summary Section -->
          <div class="summary-section">
            <h4>Total Sales: <span class="text-success">MYR <?= number_format($total_sales, 2) ?></span></h4>
            <h5>Total Completed Transactions: <span class="text-info"><?= $total_transactions ?></span></h5>
          </div>

          <!-- Chart Section -->
          <div class="chart-container">
            <div class="chart-box">
              <h5>Monthly Sales</h5>
              <canvas id="monthlySalesChart"></canvas>
            </div>
            <div class="chart-box">
              <h5>Service Type Distribution</h5>
              <canvas id="serviceTypeChart"></canvas>
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
              <span class="material-icons-outlined">contactless</span><span>Blue</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="LightTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="LightTheme">
              <span class="material-icons-outlined">light_mode</span><span>Light</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="DarkTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="DarkTheme">
              <span class="material-icons-outlined">dark_mode</span><span>Dark</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="SemiDarkTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="SemiDarkTheme">
              <span class="material-icons-outlined">contrast</span><span>Semi Dark</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="BoderedTheme">
            <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="BoderedTheme">
              <span class="material-icons-outlined">border_style</span><span>Bordered</span>
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
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- Load jsPDF and autoTable -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

  <script>
$(document).ready(function() {
  let monthlySalesChart, serviceTypeChart;

  function loadSalesReport() {
    var month = $("#monthSelect").val();
    var year = $("#yearSelect").val();
    var status = $("#statusSelect").val();
    var serviceType = $("#serviceTypeSelect").val();

    $.ajax({
      url: "backend/export_sales.php",
      type: "GET",
      data: { month: month, year: year, status: status, service_type: serviceType },
      success: function(response) {
        if (response.trim() === "<tr><td colspan='5' class='text-center'>No data available</td></tr>") {
          $("#salesTable tbody").html(response);
        } else {
          $("#salesTable tbody").html(response);
        }

        $.ajax({
          url: "backend/fetch_total_sales.php",
          type: "GET",
          data: { month: month, year: year, status: status, service_type: serviceType },
          success: function(totalSales) {
            $("#salesTable tfoot").html(totalSales);
            updateCharts(month, year);
          }
        });
      }
    });
  }

  function updateCharts(month, year) {
    $.ajax({
      url: "backend/graph/fetch_chart_data.php",
      type: "GET",
      data: { month: month, year: year },
      dataType: "json",
      success: function(data) {
        // Destroy existing charts if they exist
        if (monthlySalesChart) monthlySalesChart.destroy();
        if (serviceTypeChart) serviceTypeChart.destroy();

        // Monthly Sales Bar Chart
        const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
        monthlySalesChart = new Chart(monthlyCtx, {
          type: 'bar',
          data: {
            labels: data.monthly.labels,
            datasets: [{
              label: 'Sales (MYR)',
              data: data.monthly.values,
              backgroundColor: 'rgba(54, 162, 235, 0.8)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: { 
                beginAtZero: true,
                ticks: {
                  color: '#ffffff' // Y-axis labels (numbers) to white
                }
              },
              x: {
                ticks: {
                  color: '#ffffff' // X-axis labels (months) to white
                }
              }
            },
            plugins: { 
              legend: { 
                display: false 
              }
            }
          }
        });

        // Service Type Distribution Pie Chart
        const serviceTypeCtx = document.getElementById('serviceTypeChart').getContext('2d');
        serviceTypeChart = new Chart(serviceTypeCtx, {
          type: 'pie',
          data: {
            labels: data.service_type.labels,
            datasets: [{
              data: data.service_type.values,
              backgroundColor: ['#3adb76', '#ffcc00', '#ff4c4c'], // Colors for Electrical, Plumbing, Renovation
              borderWidth: 0
            }]
          },
          options: {
            plugins: { 
              legend: { 
                position: 'bottom',
                labels: {
                  color: '#ffffff' // Legend labels (service types) to white
                }
              }
            }
          }
        });
      }
    });
  }

  // Load default report on page load
  loadSalesReport();

  // Trigger filtering when any dropdown/input changes
  $("#monthSelect, #yearSelect, #statusSelect, #serviceTypeSelect").change(function() {
    loadSalesReport();
  });

  // Export to PDF
  $("#exportPdf").click(function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Header
    doc.setFillColor(41, 128, 185); // Blue background for header
    doc.rect(0, 0, 210, 30, 'F'); // Header background
    doc.setFontSize(20);
    doc.setTextColor(255, 255, 255); // White text for header
    doc.setFont("helvetica", "bold");
    doc.text("TROS Sales Report", 14, 20);

    // Company Name and Date (right-aligned)
    doc.setFontSize(10);
    doc.setTextColor(255, 255, 255);
    doc.setFont("helvetica", "normal");
    doc.text("Tan Resources Online Service", 150, 15, { align: "right" });
    doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 150, 22, { align: "right" });

    // Filters
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0); // Black for text
    doc.setFont("helvetica", "normal");
    let month = $("#monthSelect option:selected").text() || "All Months";
    let year = $("#yearSelect option:selected").text() || "All Years";
    let status = $("#statusSelect option:selected").text() || "All";
    let serviceType = $("#serviceTypeSelect option:selected").text() || "All";
    doc.text(`Filters - Month: ${month}, Year: ${year}, Status: ${status}, Service Type: ${serviceType}`, 14, 40);

    // Table Data
    let tableData = [];
    $("#salesTable tbody tr").each(function() {
      let rowData = [];
      $(this).find("td").each(function() {
        rowData.push($(this).text());
      });
      tableData.push(rowData);
    });

    let totalSales = $("#salesTable tfoot tr td:nth-child(2)").text() || "0.00";
    let totalTransactions = $(".summary-section h5 .text-info").text() || "0";

    // Table Styling
    doc.autoTable({
      head: [['Transaction ID', 'Amount', 'Booking Date', 'Service Type', 'Status']],
      body: tableData,
      startY: 50,
      theme: 'grid',
      headStyles: { 
        fillColor: [41, 128, 185], // Blue header
        textColor: 255, // White text
        fontSize: 12,
        fontStyle: 'bold',
        halign: 'center'
      },
      bodyStyles: { 
        fontSize: 10,
        textColor: 50,
        halign: 'center'
      },
      alternateRowStyles: { 
        fillColor: [240, 240, 240] // Light gray for alternate rows
      },
      margin: { top: 50, bottom: 50 },
      styles: { 
        lineColor: [150, 150, 150], // Gray borders
        lineWidth: 0.1
      },
      columnStyles: {
        0: { cellWidth: 50 }, // Transaction ID
        1: { cellWidth: 30 }, // Amount
        2: { cellWidth: 30 }, // Booking Date
        3: { cellWidth: 30 }, // Service Type
        4: { cellWidth: 30 }  // Status
      }
    });

    // Summary Section in PDF
    let finalY = doc.lastAutoTable.finalY + 10;
    doc.setFontSize(14);
    doc.setTextColor(41, 128, 185); // Blue for summary title
    doc.setFont("helvetica", "bold");
    doc.text("Summary", 14, finalY);

    // Summary Box
    doc.setFillColor(245, 245, 245); // Light gray background
    doc.rect(14, finalY + 5, 182, 20, 'F'); // Background box
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0); // Black for text
    doc.setFont("helvetica", "normal");
    doc.text(`Total Sales: ${totalSales}`, 16, finalY + 15); // Removed "MYR RM"
    doc.text(`Total Completed Transactions: ${totalTransactions}`, 16, finalY + 22);

    // Footer
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
      doc.setPage(i);
      // Footer Line
      doc.setDrawColor(41, 128, 185);
      doc.setLineWidth(0.5);
      doc.line(14, doc.internal.pageSize.height - 20, 196, doc.internal.pageSize.height - 20);
      // Footer Text
      doc.setFontSize(10);
      doc.setTextColor(150);
      doc.setFont("helvetica", "normal");
      doc.text(`Page ${i} of ${pageCount}`, doc.internal.pageSize.width - 30, doc.internal.pageSize.height - 10, { align: "right" });
      doc.text("© 2025 Tan Resources Online Service", 14, doc.internal.pageSize.height - 10);
    }

    let fileName = `Sales_Report_${year}_${month}.pdf`;
    doc.save(fileName);
  });
});
</script>

</body>
</html>