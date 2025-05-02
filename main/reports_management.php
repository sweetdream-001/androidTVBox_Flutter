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
    .modal-dialog {
        margin-top: 5rem; /* Adjust this value as needed */
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

    body { background: #f8f9fa; }
    .table-actions .btn { margin-right: 0.25rem; }
    .modal-lg { max-width: 900px; }
    .cpf-invalid { border-color: #dc3545 !important; }
    /* Header flex container */
    .header-container {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }
    .header-container h2 {
      margin-bottom: 0;
      margin-left: 0.5rem;
      color: #007bff;
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
        <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
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
        <a class="nav-link active" href="reports_management.php"><i class="fas fa-chart-bar"></i> Reports</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="api_management.php"><i class="fas fa-cogs"></i> API Setting</a>
      </li>
    </ul>
  </nav>
  <!-- Main Content -->
  <main class="content-wrapper" id="main-content">
    <div class="header-container">
      <h2><i class="fas fa-chart-bar"></i> Reports and Monitoring</h2>
    </div>
    
    <!-- Reports Section -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="dashboard-header mb-4">
          <h5>System Usage & Status Reports</h5>
          <div>
            <button class="btn btn-outline-primary btn-sm mr-2" onclick="exportReport('csv')"><i class="fas fa-file-csv"></i> Export CSV</button>
            <button class="btn btn-outline-danger btn-sm" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="card p-3">
              <h6>Total Active Clients</h6>
              <h3 class="text-primary">1,245</h3>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card p-3">
              <h6>Registered Devices</h6>
              <h3 class="text-primary">3,560</h3>
              <small><span class="text-success"><i class="fas fa-circle"></i> Online: 2,800</span>, <span class="text-muted"><i class="fas fa-circle"></i> Offline: 760</span></small>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card p-3">
              <h6>Media Consumption (Last 7 days)</h6>
              <h3 class="text-primary">7,890 plays</h3>
            </div>
          </div>
        </div>
    
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card p-3">
              <h6>Device Status Overview</h6>
              <canvas id="deviceStatusChart" style="height: 250px;"></canvas>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="card p-3">
              <h6>Media Usage Trend (Last 7 days)</h6>
              <canvas id="mediaUsageChart" style="height: 250px;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Access & Operation Logs -->
    <div class="card">
      <div class="card-body">
        <h5>Access and Operation Logs</h5>
        <div style="max-height: 300px; overflow-y: auto; font-family: monospace; background: #f1f1f1; padding: 1rem; border-radius: 0.375rem;">
          <pre id="logsContent">
    [2025-04-26 09:00:00] User admin logged in
    [2025-04-26 09:15:23] Device DEV-001 went offline
    [2025-04-26 09:30:45] Media "Promo Video" played on Device DEV-002
    [2025-04-26 10:05:12] User operator1 edited client "Client A"
    [2025-04-26 10:15:50] Playlist "Morning Loop" started on Device DEV-003
    [2025-04-26 10:45:30] User admin logged out
    [2025-04-26 11:00:00] Device DEV-004 came online
    [2025-04-26 11:15:10] Media "Summer Campaign Image" uploaded by User admin
          </pre>
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
    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('main-content').classList.toggle('shifted');
    });    
  </script>
      <script>
        // Chart.js charts initialization
        const deviceStatusCtx = document.getElementById('deviceStatusChart').getContext('2d');
        const deviceStatusChart = new Chart(deviceStatusCtx, {
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
      
        const mediaUsageCtx = document.getElementById('mediaUsageChart').getContext('2d');
        const mediaUsageChart = new Chart(mediaUsageCtx, {
          type: 'line',
          data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
              label: 'Media Plays',
              data: [120, 150, 180, 170, 200, 220, 190],
              borderColor: '#007bff',
              backgroundColor: 'rgba(0,123,255,0.2)',
              fill: true,
              lineTension: 0.3,
              pointRadius: 4,
              pointHoverRadius: 6,
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
      
        // Export buttons demo
        function exportReport(type) {
          alert(`Exporting report as ${type.toUpperCase()} (demo).`);
          // Implement real export logic here
        }
      </script>
</body>
</html>
