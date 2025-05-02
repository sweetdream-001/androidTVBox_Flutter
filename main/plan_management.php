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
        <a class="nav-link active" href="plan_management.php"><i class="fas fa-tags"></i> Plans & Prices</a>
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
    <div class="header-container">
      <h2><i class="fas fa-tags"></i> Plan Management</h2>
      <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#planModal" onclick="resetPlanForm()">
        <i class="fas fa-plus"></i> New Plan
      </button>
    </div>
    
    <!-- Plans Table -->
    <div class="card mb-4">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0" id="planTable">
            <thead class="thead-light">
              <tr>
                <th>Name</th>
                <th>Parameters</th>
                <th>Associated Clients</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample static rows -->
              <tr>
                <td>Basic Plan</td>
                <td>Max 10 min ads/hour</td>
                <td>Client A, Client B</td>
                <td class="table-actions">
                  <button class="btn btn-primary btn-sm" title="Edit" onclick="openPlanEdit('Basic Plan', 'Max 10 min ads/hour', ['Client A', 'Client B'])"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Premium Plan</td>
                <td>Max 20 min ads/hour</td>
                <td>Client C</td>
                <td class="table-actions">
                  <button class="btn btn-primary btn-sm" title="Edit" onclick="openPlanEdit('Premium Plan', 'Max 20 min ads/hour', ['Client C'])"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <!-- Plan Modal -->
    <div class="modal fade" id="planModal" tabindex="-1" role="dialog" aria-labelledby="planModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="modal-content" id="planForm" onsubmit="return savePlan(event)">
          <div class="modal-header">
            <h5 class="modal-title" id="planModalLabel">Register/Edit Plan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetPlanForm()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="planName">Plan Name</label>
              <input type="text" class="form-control" id="planName" required>
            </div>
            <div class="form-group">
              <label for="planParameters">Parameters</label>
              <input type="text" class="form-control" id="planParameters" placeholder="e.g. Max 10 min ads/hour" required>
            </div>
            <div class="form-group">
              <label for="planClients">Associate Clients</label>
              <select id="planClients" class="form-control" multiple>
                <option>Client A</option>
                <option>Client B</option>
                <option>Client C</option>
                <option>Client D</option>
              </select>
              <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple clients.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Plan</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetPlanForm()">Cancel</button>
          </div>
        </form>
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
        // Open plan edit modal and prefill form
        function openPlanEdit(name, parameters, clients) {
          document.getElementById('planName').value = name;
          document.getElementById('planParameters').value = parameters;
      
          const select = document.getElementById('planClients');
          // Clear previous selections
          for (let i = 0; i < select.options.length; i++) {
            select.options[i].selected = false;
          }
          // Select clients passed in array
          clients.forEach(client => {
            for (let i = 0; i < select.options.length; i++) {
              if (select.options[i].text === client) {
                select.options[i].selected = true;
                break;
              }
            }
          });
      
          $('#planModal').modal('show');
        }
      
        // Save plan (demo)
        function savePlan(event) {
          event.preventDefault();
          alert('Plan saved (demo).');
          $('#planModal').modal('hide');
          resetPlanForm();
          return false;
        }
      
        // Reset form
        function resetPlanForm() {
          document.getElementById('planForm').reset();
          const select = document.getElementById('planClients');
          for (let i = 0; i < select.options.length; i++) {
            select.options[i].selected = false;
          }
        }
      </script>
</body>
</html>
