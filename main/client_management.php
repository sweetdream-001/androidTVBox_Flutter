<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination setup
$limit = 10; // Number of entries to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Calculate the offset

// Fetch total number of clients for pagination
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM clients");
$total_row = mysqli_fetch_assoc($total_result);
$total_clients = $total_row['total'];
$total_pages = ceil($total_clients / $limit); // Total number of pages

// Fetch clients for the current page
$sql1 = "SELECT * FROM clients LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql1);

// Handle add/update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $cpf = mysqli_real_escape_string($conn, $_POST["cpf"]);
    $street = mysqli_real_escape_string($conn, $_POST["street"]);
    $number = mysqli_real_escape_string($conn, $_POST["number"]);
    $complement = mysqli_real_escape_string($conn, $_POST["complement"]);
    $neighborhood = mysqli_real_escape_string($conn, $_POST["neighborhood"]);
    $city = mysqli_real_escape_string($conn, $_POST["city"]);
    $state = mysqli_real_escape_string($conn, $_POST["state"]);
    $zipcode = mysqli_real_escape_string($conn, $_POST["zipcode"]);
    $latitude = mysqli_real_escape_string($conn, $_POST["latitude"]);
    $longitude = mysqli_real_escape_string($conn, $_POST["longitude"]);
    $segment = mysqli_real_escape_string($conn, $_POST["segment"]);
    $edit_mode = isset($_POST["edit_mode"]) ? $_POST["edit_mode"] : "";

    if ($edit_mode == "1" && !empty($cpf)) {
        // Update existing client
        $sql = "UPDATE clients SET 
            FullName='$fullname', Street='$street', StreetNumber='$number', 
            Complement='$complement', Neighborhood='$neighborhood',
            City='$city', State='$state', ZipCode='$zipcode', 
            Latitude='$latitude', Longitude='$longitude', MarketSegment='$segment'
            WHERE CPF='$cpf'";
    } else {
        // Insert new client
        $sql = "INSERT INTO clients 
            (FullName, CPF, Street, StreetNumber, Complement, Neighborhood, City, State, ZipCode, Latitude, Longitude, MarketSegment)
            VALUES ('$fullname', '$cpf', '$street', '$number', '$complement', '$neighborhood', '$city', '$state', '$zipcode', '$latitude', '$longitude', '$segment')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Refresh:0");
        exit;
    } else {
        echo "<script>alert('Error saving client: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Client Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <nav class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="client_management.php"><i class="fas fa-users"></i> Clients Management</a>
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

    <main class="content-wrapper" id="main-content">
        <div class="header-container">
            <h2><i class="fas fa-users"></i> Client Management</h2>
            <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#clientModal" onclick="openClientForm()">
                <i class="fas fa-plus"></i> New Client
            </button>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Search clients...">
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="clientTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>CPF</th>
                                <th>City</th>
                                <th>Market Segment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientTableBody">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['FullName']) ?></td>
                                <td><?= htmlspecialchars($row['CPF']) ?></td>
                                <td><?= htmlspecialchars($row['City']) ?></td>
                                <td><?= htmlspecialchars($row['MarketSegment']) ?></td>
                                <td class="table-actions">
                                    <button class="btn btn-info btn-sm" title="View" onclick="openClientDetail('<?= htmlspecialchars($row['CPF']) ?>')"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-primary btn-sm" title="Edit" onclick="editClient('<?= htmlspecialchars($row['CPF']) ?>')"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" title="Delete" onclick="deleteClient('<?= htmlspecialchars($row['CPF']) ?>')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div class="d-flex justify-content-end">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Client Form Modal -->
        <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form class="modal-content" id="clientForm" method="post" action="" onsubmit="return validateCPF()">
                    <input type="hidden" name="edit_mode" id="edit_mode" value="0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clientModalLabel">Client Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="fullname" id="form_fullname" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>CPF</label>
                                <input type="text" class="form-control" name="cpf" id="form_cpf" maxlength="14" placeholder="000.000.000-00" required readonly>
                                <small id="cpfHelp" class="form-text text-danger d-none">Invalid CPF format.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Full Address</label>
                            <div class="form-row">
                                <div class="col-md-4 mb-2"><input type="text" class="form-control" name="street" id="form_street" placeholder="Street" required></div>
                                <div class="col-md-2 mb-2"><input type="text" class="form-control" name="number" id="form_number" placeholder="Number" required></div>
                                <div class="col-md-2 mb-2"><input type="text" class="form-control" name="complement" id="form_complement" placeholder="Complement"></div>
                                <div class="col-md-4 mb-2"><input type="text" class="form-control" name="neighborhood" id="form_neighborhood" placeholder="Neighborhood" required></div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-2"><input type="text" class="form-control" name="city" id="form_city" placeholder="City" required></div>
                                <div class="col-md-2 mb-2"><input type="text" class="form-control" name="state" id="form_state" placeholder="State" required></div>
                                <div class="col-md-6 mb-2"><input type="text" class="form-control" name="zipcode" id="form_zipcode" placeholder="Zip Code" required></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Latitude</label>
                                <input type="text" class="form-control" name="latitude" id="form_latitude" placeholder="-23.5505">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Longitude</label>
                                <input type="text" class="form-control" name="longitude" id="form_longitude" placeholder="-46.6333">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Market Segment</label>
                            <select class="form-control" name="segment" id="form_segment" required>
                                <option value="">Select segment</option>
                                <option>Retail</option>
                                <option>Healthcare</option>
                                <option>Education</option>
                                <option>Finance</option>
                                <option>Industry</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Client</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetClientForm()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Client Detail Modal -->
        <div class="modal fade" id="clientDetailModal" tabindex="-1" role="dialog" aria-labelledby="clientDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clientDetailModalLabel">Client Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" id="clientDetailBody">
                        <!-- Client info loaded dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function resetClientForm() {
            $('#clientForm')[0].reset();
            $('#edit_mode').val("0");
            $('#form_cpf').prop('readonly', false);
        }

        function openClientForm() {
            resetClientForm();
            $('#clientModal').modal('show');
        }

        function editClient(cpf) {
            $.ajax({
                url: 'get_client.php',
                type: 'GET',
                data: { cpf: cpf },
                dataType: 'json',
                success: function(data) {
                    $('#form_fullname').val(data.FullName);
                    $('#form_cpf').val(data.CPF).prop('readonly', true);
                    $('#form_street').val(data.Street);
                    $('#form_number').val(data.StreetNumber);
                    $('#form_complement').val(data.Complement);
                    $('#form_neighborhood').val(data.Neighborhood);
                    $('#form_city').val(data.City);
                    $('#form_state').val(data.State);
                    $('#form_zipcode').val(data.ZipCode);
                    $('#form_latitude').val(data.Latitude);
                    $('#form_longitude').val(data.Longitude);
                    $('#form_segment').val(data.MarketSegment);
                    $('#edit_mode').val("1");
                    $('#clientModal').modal('show');
                },
                error: function() {
                    alert('Could not fetch client data.');
                }
            });
        }

        function openClientDetail(cpf) {
            $.ajax({
                url: 'get_client_detail.php',
                type: 'GET',
                data: { cpf: cpf },
                success: function(data) {
                    $('#clientDetailBody').html(data);
                    $('#clientDetailModal').modal('show');
                },
                error: function() {
                    alert('Error fetching client details.');
                }
            });
        }

        function deleteClient(cpf) {
            if (confirm("Are you sure you want to delete this client?")) {
                $.ajax({
                    url: 'delete_client.php',
                    type: 'POST',
                    data: { cpf: cpf },
                    success: function(response) {
                            location.reload(); // Refresh the page to see the changes
                    },
                    error: function() {
                        alert('Error deleting client.');
                    }
                });
            }
        }

        function validateCPF() {
            var cpf = document.getElementById('form_cpf').value;
            var cpfPattern = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
            if (!cpfPattern.test(cpf)) {
                document.getElementById('form_cpf').classList.add('cpf-invalid');
                document.getElementById('cpfHelp').classList.remove('d-none');
                return false;
            } else {
                document.getElementById('form_cpf').classList.remove('cpf-invalid');
                document.getElementById('cpfHelp').classList.add('d-none');
                return true;
            }
        }

        document.getElementById('searchInput').addEventListener('keyup', function() {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#clientTableBody tr');
            rows.forEach(function(row) {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>