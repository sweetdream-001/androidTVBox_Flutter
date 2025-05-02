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
        <a class="nav-link active" href="access_management.php"><i class="fas fa-user-shield"></i> Access Control</a>
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
      <h2><i class="fas fa-user-shield"></i> Access and User Control</h2>
      <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#userModal" onclick="resetUserForm()">
        <i class="fas fa-user-plus"></i> New User
      </button>
    </div>
    
    <!-- Users Table -->
    <div class="card mb-4">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0" id="usersTable">
            <thead class="thead-light">
              <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Permissions</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample static users -->
              <tr>
                <td>admin</td>
                <td>admin@example.com</td>
                <td>Administrator</td>
                <td>All Modules / All Actions</td>
                <td class="table-actions">
                  <button class="btn btn-primary btn-sm" title="Edit" onclick="openUserEdit('admin', 'admin@example.com', 'Administrator', ['dashboard:view', 'users:edit', 'reports:view'])"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-danger btn-sm" title="Delete" onclick="deleteUser('admin')"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>operator1</td>
                <td>operator1@example.com</td>
                <td>Operator</td>
                <td>Dashboard:view, Devices:manage</td>
                <td class="table-actions">
                  <button class="btn btn-primary btn-sm" title="Edit" onclick="openUserEdit('operator1', 'operator1@example.com', 'Operator', ['dashboard:view', 'devices:manage'])"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-danger btn-sm" title="Delete" onclick="deleteUser('operator1')"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
              <!-- Add more users dynamically -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <!-- User Registration/Edit Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="userForm" onsubmit="return saveUser(event)">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Register/Edit User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetUserForm()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- User Info -->
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="usernameInput">Username</label>
                <input type="text" class="form-control" id="usernameInput" required>
              </div>
              <div class="form-group col-md-6">
                <label for="emailInput">Email</label>
                <input type="email" class="form-control" id="emailInput" required>
              </div>
            </div>
            <div class="form-group">
              <label for="roleSelect">Role / Profile</label>
              <select id="roleSelect" class="form-control" required onchange="updatePermissionsByRole()">
                <option value="">Select role</option>
                <option value="Administrator">Administrator</option>
                <option value="Financial">Financial</option>
                <option value="Operator">Operator</option>
              </select>
            </div>
    
            <!-- Permissions -->
            <div>
              <label>Permissions (check modules and actions):</label>
              <div class="border rounded p-3" style="max-height:300px; overflow-y:auto;">
                <div class="form-group">
                  <div><strong>Dashboard</strong></div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm_dashboard_view" value="dashboard:view">
                    <label class="form-check-label" for="perm_dashboard_view">View</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm_dashboard_edit" value="dashboard:edit">
                    <label class="form-check-label" for="perm_dashboard_edit">Edit</label>
                  </div>
                </div>
    
                <div class="form-group">
                  <div><strong>Users</strong></div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm_users_view" value="users:view">
                    <label class="form-check-label" for="perm_users_view">View</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm_users_edit" value="users:edit">
                    <label class="form-check-label" for="perm_users_edit">Edit</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm_users_delete" value="users:delete">
                    <label class="form-check-label" for="perm_users_delete">Delete</label>
                  </div>
                </div>
    
                <div class="form-group">
                  <div><strong>Reports</strong></div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm_reports_view" value="reports:view">
                    <label class="form-check-label" for="perm_reports_view">View</label>
                  </div>
                </div>
    
                <div class="form-group">
                  <div><strong>Devices</strong></div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input permission-checkbox" type="checkbox" id="perm_devices_manage" value="devices:manage">
                    <label class="form-check-label" for="perm_devices_manage">Manage</label>
                  </div>
                </div>
    
                <!-- Add more modules/actions as needed -->
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save User</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetUserForm()">Cancel</button>
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
      // Open user edit modal and prefill form
      function openUserEdit(username, email, role, permissions) {
        document.getElementById('usernameInput').value = username;
        document.getElementById('emailInput').value = email;
        document.getElementById('roleSelect').value = role;
        updatePermissionsByRole(); // Set permissions based on role first
    
        // Clear all checkboxes first
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    
        // Set checkboxes for permissions array
        permissions.forEach(perm => {
          const checkbox = document.querySelector(`.permission-checkbox[value="${perm}"]`);
          if (checkbox) checkbox.checked = true;
        });
    
        $('#userModal').modal('show');
      }
    
      // Reset user form
      function resetUserForm() {
        document.getElementById('userForm').reset();
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
      }
    
      // Save user (demo)
      function saveUser(event) {
        event.preventDefault();
        const username = document.getElementById('usernameInput').value.trim();
        const email = document.getElementById('emailInput').value.trim();
        const role = document.getElementById('roleSelect').value;
        const permissions = Array.from(document.querySelectorAll('.permission-checkbox:checked')).map(cb => cb.value);
    
        // Simple validation
        if (!username || !email || !role) {
          alert('Please fill all required fields.');
          return false;
        }
    
        // Here you would send data to backend
        alert(`User saved (demo):\nUsername: ${username}\nEmail: ${email}\nRole: ${role}\nPermissions: ${permissions.join(', ')}`);
    
        $('#userModal').modal('hide');
        resetUserForm();
        return false;
      }
    
      // Delete user (demo)
      function deleteUser(username) {
        if (confirm(`Are you sure you want to delete user "${username}"?`)) {
          alert(`User "${username}" deleted (demo).`);
          // Implement deletion logic here
        }
      }
    
      // Update permissions checkboxes based on role selection
      function updatePermissionsByRole() {
        const role = document.getElementById('roleSelect').value;
        const checkboxes = document.querySelectorAll('.permission-checkbox');
    
        // Clear all first
        checkboxes.forEach(cb => cb.checked = false);
    
        if (role === 'Administrator') {
          // Admin gets all permissions
          checkboxes.forEach(cb => cb.checked = true);
        } else if (role === 'Financial') {
          // Financial role example permissions
          ['reports:view', 'users:view'].forEach(perm => {
            const cb = document.querySelector(`.permission-checkbox[value="${perm}"]`);
            if (cb) cb.checked = true;
          });
        } else if (role === 'Operator') {
          // Operator example permissions
          ['dashboard:view', 'devices:manage'].forEach(perm => {
            const cb = document.querySelector(`.permission-checkbox[value="${perm}"]`);
            if (cb) cb.checked = true;
          });
        }
      }
    </script>
</body>
</html>
