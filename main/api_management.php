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
        <a class="nav-link" href="reports_management.php"><i class="fas fa-chart-bar"></i> Reports</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="api_management.php"><i class="fas fa-cogs"></i> API Setting</a>
      </li>
    </ul>
  </nav>

  <!-- Main Content -->
  <main class="content-wrapper" id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2><i class="fas fa-plug"></i> API Integrations</h2>
      <button class="btn btn-success" data-toggle="modal" data-target="#apiModal" onclick="resetApiForm()">
        <i class="fas fa-plus"></i> Add API
      </button>
    </div>
    
    <!-- API Integrations Table -->
    <div class="card">
      <div class="card-body p-0">
        <table class="table mb-0" id="apiTable">
          <thead class="thead-light">
            <tr>
              <th>Name</th><th>URL</th><th>Key</th><th>Status</th><th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
    
    <!-- API Integration Modal -->
  <div class="modal fade" id="apiModal" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" onsubmit="return saveApi(event)" id="apiForm">
        <div class="modal-header">
          <h5 class="modal-title">API Integration</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="apiId">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" id="apiName" required>
          </div>
          <div class="form-group">
            <label>URL</label>
            <input type="url" class="form-control" id="apiUrl" required>
          </div>
          <div class="form-group">
            <label>Auth Key</label>
            <input type="text" class="form-control" id="apiKey" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select class="form-control" id="apiStatus">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>

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
    function loadApiList() {
      fetch('api_management_handler.php')
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#apiTable tbody');
          tbody.innerHTML = '';
          data.forEach(api => {
            const row = document.createElement('tr');

            row.innerHTML = `
              <td>${api.name}</td>
              <td>${api.url}</td>
              <td>${api.auth_key}</td>
              <td><span class="badge badge-${api.status == 1 ? 'success' : 'danger'}">${api.status == 1 ? 'Active' : 'Inactive'}</span></td>
              <td>
                <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="deleteApi(${api.id})"><i class="fas fa-trash"></i></button>
              </td>
            `;

            // Safely store object
            row.querySelector('.btn-primary').addEventListener('click', () => {
              editApi(api);
            });

            tbody.appendChild(row);
          });
        });
    }


    function saveApi(e) {
      e.preventDefault();
      const data = {
        id: document.getElementById('apiId').value || 0,
        name: document.getElementById('apiName').value,
        url: document.getElementById('apiUrl').value,
        auth_key: document.getElementById('apiKey').value,
        status: document.getElementById('apiStatus').value
      };
      fetch('api_management_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      }).then(res => res.json()).then(() => {
        $('#apiModal').modal('hide');
        loadApiList();
      });
    }

    function editApi(api) {
      document.getElementById('apiId').value = api.id;
      document.getElementById('apiName').value = api.name;
      document.getElementById('apiUrl').value = api.url;
      document.getElementById('apiKey').value = api.auth_key;
      document.getElementById('apiStatus').value = api.status;
      $('#apiModal').modal('show');
    }

    function deleteApi(id) {
      if (!confirm('Delete this API?')) return;
      fetch('api_management_handler.php?id=' + id, { method: 'DELETE' })
        .then(res => res.json()).then(() => loadApiList());
    }

    function resetApiForm() {
      document.getElementById('apiForm').reset();
      document.getElementById('apiId').value = '';
    }

    document.addEventListener('DOMContentLoaded', loadApiList);
  </script>
</body>
</html>
