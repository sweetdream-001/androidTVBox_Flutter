
<?php
// Database connection (adjust credentials as needed)
$conn = new mysqli( "localhost",  "root",  "",  "test2");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all devices for table
$devices = [];
$res = $conn->query("SELECT * FROM devices ORDER BY last_communication DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $devices[] = $row;
    }
}

// Handle device save (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_device') {

  $device_name = $conn->real_escape_string($_POST['deviceType']);
  $brand = $conn->real_escape_string($_POST['deviceBrand']);
  $model = $conn->real_escape_string($_POST['deviceModel']);
  $mac = $conn->real_escape_string($_POST['deviceMac']);
  $client = $conn->real_escape_string($_POST['deviceClient']);
  $status = "Online";
  $last_communication = date('Y-m-d H:i:s');

  $sql = "INSERT INTO devices (name, brand, model, mac_address, client, status, last_communication)
          VALUES ('$device_name', '$brand', '$model', '$mac', '$client', '$status', '$last_communication')";
  if ($conn->query($sql)) {
    header("Location: " . $_SERVER['PHP_SELF']);
      exit;
  } else {
      echo json_encode(['success' => false, 'message' => $conn->error]);
      exit;
  }
}

// Fetch clients for modal display
$clients = [];
$sql = "SELECT fullname FROM clients ORDER BY fullname ASC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row['fullname'];
    }
}
?>

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

    /* Device Management Styles */
    .table-actions .btn { margin-right: 0.25rem; }
    .modal-lg { max-width: 900px; }
    .status-online { color: #28a745; font-weight: 600; }
    .status-offline { color: #dc3545; font-weight: 600; }
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
        <a class="nav-link active" href="device_management.php"><i class="fas fa-tv"></i> Device Management</a>
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
    <div class="header-container">
      <h2><i class="fas fa-tv"></i> Device Management</h2>
      <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#deviceModal" >
        <i class="fas fa-plus"></i> Register Device
      </button>
    </div>

    <!-- Filters -->
    <form id="filterForm" class="form-inline mb-3">
      <div class="form-group mr-3">
        <label for="filterClient" class="mr-2">Client</label>
        <select id="filterClient" name="filterClient" class="form-control">
        <option value="">Select Client</option>
          <?php foreach ($clients as $clientName): ?>
            <option value="<?= htmlspecialchars($clientName) ?>"><?= htmlspecialchars($clientName) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group mr-3">
        <label for="filterType" class="mr-2">Device Name</label>
        <select id="filterType" name="filterType" class="form-control">
          <option value="">All Types</option>
          <option>TV Box</option>
          <option>Smart TV</option>
        </select>
      </div>
      <div class="form-group mr-3">
        <label for="filterStatus" class="mr-2">Status</label>
        <select id="filterStatus" name="filterStatus" class="form-control">
          <option value="">All Statuses</option>
          <option>Online</option>
          <option>Offline</option>
        </select>
      </div>
      <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply</button>
      <button type="button" class="btn btn-secondary ml-2" onclick="resetFilters()">Reset</button>
    </form>

    <!-- Device Table -->
    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0" id="deviceTable">
            <thead class="thead-light">
              <tr>
                <th>Device Name</th>
                <th>Brand</th>
                <th>Model</th>
                <th>MAC Address</th>
                <th>Client</th>
                <th>Status</th>
                <th>Last Communication</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="deviceTableBody">
              <!-- Sample static rows -->
              <?php foreach ($devices as $device): ?>
              <tr>
                <td><?= htmlspecialchars($device['name']) ?></td>
                <td><?= htmlspecialchars($device['brand']) ?></td>
                <td><?= htmlspecialchars($device['model']) ?></td>
                <td><?= htmlspecialchars($device['mac_address']) ?></td>
                <td><?= htmlspecialchars($device['client']) ?></td>
                <td>
                  <?php if ($device['status'] === 'Online'): ?>
                    <span class="status-online">Online</span>
                  <?php else: ?>
                    <span class="status-offline">Offline</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($device['last_communication']) ?></td>
                <td><button class="btn btn-info btn-sm" title="View" onclick="openDeviceDetail()"><i class="fas fa-eye"></i></button>
                <button class="btn btn-primary btn-sm" title="Edit" onclick="openDeviceForm()"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($devices)): ?>
                <tr><td colspan="7" class="text-center">No devices found.</td></tr>
              <?php endif; ?>

            </tbody>
          </table>
        </div>
      </div>
      <!-- Pagination (static demo) -->
      <div class="card-footer d-flex justify-content-end">
        <nav>
          <ul class="pagination mb-0">
            <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- Device Modal -->
    <div class="modal fade" id="deviceModal" tabindex="-1" role="dialog" aria-labelledby="deviceModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
      <form class="modal-content" id="deviceForm" method="post" action="">
          <input type="hidden" name="action" value="save_device">
          <div class="modal-header">
            <h5 class="modal-title" id="deviceModalLabel">Device Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetDeviceForm()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="deviceType">Device Name</label>
                <select id="deviceType" name="deviceType" class="form-control"  required>
                  <option value="">Select type</option>
                  <option>TV Box</option>
                  <option>Smart TV</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="deviceClient">Client</label>
                <select id="deviceClient" name="deviceClient" class="form-control" required>
                <option value="">Select Client</option>
                <?php foreach ($clients as $clientName): ?>
                  <option value="<?= htmlspecialchars($clientName) ?>"><?= htmlspecialchars($clientName) ?></option>
                <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="deviceBrand">Brand</label>
                <input type="text" id="deviceBrand" name="deviceBrand" class="form-control" required>
              </div>
              <div class="form-group col-md-6">
                <label for="deviceModel">Model</label>
                <input type="text" id="deviceModel" name="deviceModel" class="form-control" required>
              </div>
            </div>
            <div class="form-group">
              <label for="deviceMac">MAC Address</label>
              <input type="text" id="deviceMac" name="deviceMac" class="form-control" placeholder="00:00:00:00:00:00" pattern="^([0-9A-Fa-f]{2}:){5}([0-9A-Fa-f]{2})$" required>
              <small class="form-text text-muted">Format: 00:00:00:00:00:00</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Device</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetDeviceForm()">Cancel</button>
          </div>
      </form>
      </div>
    </div>

    <!-- Device Detail Modal -->
    <div class="modal fade" id="deviceDetailModal" tabindex="-1" role="dialog" aria-labelledby="deviceDetailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deviceDetailModalLabel">Device Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetDeviceDetail()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="deviceDetailBody">
            <!-- Filled dynamically -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetDeviceDetail()">Close</button>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- Bootstrap 4 JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // document.getElementById('deviceForm').addEventListener('submit', function(event) {
    //   event.preventDefault();
    //   saveDevice(event);
    // });

    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('main-content').classList.toggle('shifted');
    });

    // Reset device form
    function resetDeviceForm() {
      document.getElementById('deviceForm').reset();
    }

    // Open device detail modal with info (static demo)
    function openDeviceDetail() {
      const detailBody = document.getElementById('deviceDetailBody');
      detailBody.innerHTML = `
        <h5>Device Information</h5>
        <p><strong>Type:</strong> TV Box</p>
        <p><strong>Brand:</strong> Samsung</p>
        <p><strong>Model:</strong> Q90T</p>
        <p><strong>MAC Address:</strong> 00:1A:2B:3C:4D:5E</p>
        <p><strong>Client:</strong> Maria Silva</p>
        <p><strong>Status:</strong> <span class="status-online">Online</span></p>
        <p><strong>Last Communication:</strong> 2025-04-26 10:15 AM</p>
      `;
      $('#deviceDetailModal').modal('show');
    }

    // Reset device detail modal content
    function resetDeviceDetail() {
      document.getElementById('deviceDetailBody').innerHTML = '';
    }

    // Filter devices (basic client-side filter demo)
    function applyFilters() {
      const clientFilter = document.getElementById('filterClient').value.toLowerCase();
      const typeFilter = document.getElementById('filterType').value.toLowerCase();
      const statusFilter = document.getElementById('filterStatus').value.toLowerCase();

      const rows = document.querySelectorAll('#deviceTableBody tr');
      rows.forEach(row => {
        const client = row.cells[4].textContent.toLowerCase();
        const type = row.cells[0].textContent.toLowerCase();
        const status = row.cells[5].textContent.toLowerCase();

        const show = 
          (clientFilter === '' || client.includes(clientFilter)) &&
          (typeFilter === '' || type.includes(typeFilter)) &&
          (statusFilter === '' || status.includes(statusFilter));

        row.style.display = show ? '' : 'none';
      });
    }

    function openDeviceForm(){
      document.getElementById()
    }

    // Reset filters and show all rows
    function resetFilters() {
      document.getElementById('filterClient').value = '';
      document.getElementById('filterType').value = '';
      document.getElementById('filterStatus').value = '';
      applyFilters();
    }

  </script>
</body>
</html>
