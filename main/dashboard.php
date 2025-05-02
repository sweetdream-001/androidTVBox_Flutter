<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <title>Main Dashboard</title>
  <!-- Bootstrap 4 CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>
    body {
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    /* Navbar */
    .navbar {
      background: #343a40;
      height: 56px;
      padding: 0 1rem;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1100;
    }
    .navbar .navbar-brand, 
    .navbar .nav-link, 
    .navbar .navbar-text {
      color: #fff;
    }
    .navbar .nav-link:hover {
      color: #ffc107;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 56px; /* height of navbar */
      left: 0;
      width: 250px;
      height: 100vh;
      background: #343a40;
      color: #fff;
      padding-top: 1rem;
      overflow-y: auto;
      z-index: 1000;
      transition: all 0.3s;
    }
    .sidebar .nav-link {
      color: #fff;
      padding: 12px 18px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      border-radius: 4px;
      margin-bottom: 2px;
      display: flex;
      align-items: center;
    }
    .sidebar .nav-link.active, .sidebar .nav-link:hover {
      background: #495057;
      color: #ffc107;
      text-decoration: none;
    }
    .sidebar .nav-link i {
      margin-right: 10px;
      min-width: 20px;
      text-align: center;
    }

    /* Main content */
    .content-wrapper {
      margin-left: 240px;
      padding: 76px 24px 24px 24px; /* top padding for navbar, left for sidebar */
      min-height: 100vh;
      transition: all 0.3s;
    }

    /* KPI card styles */
    .card {
      border-radius: 0.75rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .kpi-icon {
      font-size: 2.5rem;
      color: #007bff;
    }
    .alert-item {
      border-left: 4px solid;
      padding-left: 12px;
      margin-bottom: 10px;
    }
    .alert-offline {
      border-color: #dc3545;
    }
    .alert-defaulting {
      border-color: #ffc107;
    }
    .chart-container {
      min-height: 250px;
    }
    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
      .sidebar {
        left: -240px;
      }
      .sidebar.active {
        left: 0;
      }
      .content-wrapper {
        margin-left: 0;
        padding-left: 16px;
        padding-right: 16px;
      }
      .content-wrapper.shifted {
        margin-left: 240px;
      }
      .sidebar-toggle {
        display: inline-block;
      }
    }
    @media (min-width: 992px) {
      .sidebar-toggle {
        display: none;
      }
    }
  </style>
</head>
<body>
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand navbar-dark">
    <button class="sidebar-toggle btn btn-link d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
    <a class="navbar-brand" href="#">Admin Panel</a>
    <ul class="navbar-nav ml-auto align-items-center">
      <li class="nav-item">
        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="client_management.php"><i class="fas fa-users"></i> Clients Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="plan_management.php"><i class="fas fa-tags"></i> Plans & Prices</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="media_management.php"><i class="fas fa-photo-video"></i> Media Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="playlist.php"><i class="fas fa-list"></i> Playlists</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="device_management.php"><i class="fas fa-tv"></i> Device Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="access_management.php"><i class="fas fa-user-shield"></i> Access Control</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="fas fa-toggle-on"></i> Activate/Deactivate Clients</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="reports_management.php"><i class="fas fa-chart-bar"></i> Reports</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="api_management.php"><i class="fas fa-cogs"></i> API Setting</a>
      </li>
    </ul>
  </nav>

  <!-- Main Content -->
  <main class="content-wrapper" id="main-content">

    <!-- KPI Cards -->
    <div class="row">
      <div class="col-md-3 mb-4">
        <div class="card p-3 text-center">
          <div><i class="fas fa-users kpi-icon"></i></div>
          <h5 class="mt-2">Active Clients</h5>
          <h2 class="font-weight-bold">1,245</h2>
        </div>
  </div>
      <div class="col-md-3 mb-4">
        <div class="card p-3 text-center">
          <div><i class="fas fa-desktop kpi-icon"></i></div>
          <h5 class="mt-2">Registered Devices</h5>
          <h2 class="font-weight-bold">3,560</h2>
          <small class="text-success"><i class="fas fa-circle"></i> Online: 2,800</small><br/>
          <small class="text-muted"><i class="fas fa-circle"></i> Offline: 760</small>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card p-3 text-center">
          <div><i class="fas fa-photo-video kpi-icon"></i></div>
          <h5 class="mt-2">Registered Media</h5>
          <h2 class="font-weight-bold">7,890</h2>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card p-3 text-center">
          <div><i class="fas fa-list kpi-icon"></i></div>
          <h5 class="mt-2">Active Playlists</h5>
          <h2 class="font-weight-bold">120</h2>
        </div>
      </div>
    </div>

    <!-- Recent Alerts and Charts -->
    <div class="row">
      <!-- Recent Alerts -->
      <div class="col-lg-4 mb-4">
        <div class="card p-3">
          <h4 class="card-title mb-3">Recent Alerts</h4>
          <div class="alert-item alert-offline">
            <strong>Device Offline:</strong> Device #1234 has been offline for 2 hours.
            <br /><small class="text-muted">2025-04-26 10:30 AM</small>
          </div>
          <div class="alert-item alert-defaulting">
            <strong>Defaulting Client:</strong> Client XYZ missed last 3 payments.
            <br /><small class="text-muted">2025-04-25 03:15 PM</small>
          </div>
          <div class="alert-item alert-offline">
            <strong>Device Offline:</strong> Device #5678 has been offline for 30 minutes.
            <br /><small class="text-muted">2025-04-26 11:45 AM</small>
          </div>
          <a href="#" class="btn btn-link btn-sm mt-2">View all alerts</a>
        </div>
      </div>

      <!-- Charts -->
      <div class="col-lg-8">
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card p-3">
              <h5 class="card-title">Device Status Overview</h5>
              <div class="chart-container">
                <canvas id="deviceStatusChart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="card p-3">
              <h5 class="card-title">Media Usage (Last 7 Days)</h5>
              <div class="chart-container">
                <canvas id="mediaUsageChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Bootstrap 4 JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Device Status Doughnut Chart
    var ctx1 = document.getElementById('deviceStatusChart').getContext('2d');
    var deviceStatusChart = new Chart(ctx1, {
      type: 'doughnut',
      data: {
        labels: ['Online', 'Offline'],
        datasets: [{
          data: [2800, 760],
          backgroundColor: ['#28a745', '#dc3545'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        legend: { position: 'bottom' },
        cutoutPercentage: 60,
      }
    });

    // Media Usage Bar Chart
    var ctx2 = document.getElementById('mediaUsageChart').getContext('2d');
    var mediaUsageChart = new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'Media Played',
          data: [120, 150, 180, 170, 200, 220, 190],
          backgroundColor: '#007bff'
        }]
      },
      options: {
        responsive: true,
        scales: {
          yAxes: [{
            ticks: { beginAtZero: true }
          }]
        }
      }
    });

    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('main-content').classList.toggle('shifted');
    });
  </script>
</body>
</html>
