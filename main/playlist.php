<?php
$conn = new mysqli("localhost", "root", "", "test2");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle playlist save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_playlist') {
    $name = $conn->real_escape_string($_POST['playlistName']);
    $description = $conn->real_escape_string($_POST['playlistDescription']);
    $schedule = $conn->real_escape_string($_POST['playlistSchedule']);
    $duration = $conn->real_escape_string($_POST['playlistDuration']);
    $devices = $_POST['playlistDevices'] ?? [];
    $media_files = $_POST['media_files'] ?? [];
    $media_order = $_POST['media_order'] ?? [];

    $conn->begin_transaction();
    try {
        $conn->query("INSERT INTO playlists (name, description, schedule, duration) VALUES ('$name', '$description', '$schedule', '$duration')");
        $playlist_id = $conn->insert_id;

        // Save associations: for each device, for each media
        $position = 1;
        if (!is_array($devices)) {
          $devices = [$devices];  // Wrap single value into array
        }
        foreach ($devices as $device_id) {
        if (!is_array($media_order)){
          $media_order = [$media_order];
        }
            foreach ($media_order as $media_file_id) {
                if (in_array($media_file_id, $media_files)) {
                    $conn->query("INSERT INTO playlist_device_media (playlist_id, device_id, media_file_id, position)
                                  VALUES ($playlist_id, $device_id, $media_file_id, $position)");
                }
                $position++;
            }
        }
        $conn->commit();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
    
}

// Fetch devices and media for the form
$devices = $conn->query("SELECT id, name FROM devices");
$media_files = $conn->query("SELECT id, file_name, file_type FROM media_files");

// Fetch playlists and associated media count per device
$playlists = [];
$sql = "SELECT p.*, 
        GROUP_CONCAT(DISTINCT d.name) AS devices
        FROM playlists p
        LEFT JOIN playlist_device_media pdm ON pdm.playlist_id = p.id
        LEFT JOIN devices d ON d.id = pdm.device_id
        GROUP BY p.id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    // For each device, count associated media
    $device_media_counts = [];
    $device_ids = [];
    if ($row['devices']) {
        $device_names = explode(',', $row['devices']);
        foreach ($device_names as $dev_name) {
            $dev_name = trim($dev_name);
            $cnt_result = $conn->query("SELECT COUNT(*) AS cnt FROM playlist_device_media pdm
                                        JOIN devices d ON d.id = pdm.device_id
                                        WHERE pdm.playlist_id = {$row['id']} AND d.name = '$dev_name'");
            $cnt_row = $cnt_result->fetch_assoc();
            $device_media_counts[] = "$dev_name ({$cnt_row['cnt']})";
        }
    }
    echo $device_media_counts[0];
    $row['device_media_counts'] = implode(', ', $device_media_counts);
    $playlists[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <title>Playlist Management</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
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
<nav class="navbar navbar-expand navbar-dark">
  <button class="sidebar-toggle btn btn-link d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
  <a class="navbar-brand" href="#">Admin Panel</a>
  <ul class="navbar-nav ml-auto align-items-center">
    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</nav>
<nav class="sidebar" id="sidebar">
  <ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="client_management.php"><i class="fas fa-users"></i> Clients Management</a></li>
    <li class="nav-item"><a class="nav-link" href="plan_management.php"><i class="fas fa-tags"></i> Plans & Prices</a></li>
    <li class="nav-item"><a class="nav-link" href="media_management.php"><i class="fas fa-photo-video"></i> Media Management</a></li>
    <li class="nav-item"><a class="nav-link active" href="playlist.php"><i class="fas fa-list"></i> Playlists</a></li>
    <li class="nav-item"><a class="nav-link" href="device_management.php"><i class="fas fa-tv"></i> Device Management</a></li>
    <li class="nav-item"><a class="nav-link" href="access_management.php"><i class="fas fa-user-shield"></i> Access Control</a></li>
    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-toggle-on"></i> Activate/Deactivate Clients</a></li>
    <li class="nav-item"><a class="nav-link" href="reports_management.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
    <li class="nav-item"><a class="nav-link" href="api_management.php"><i class="fas fa-cogs"></i> API Setting</a></li>
  </ul>
</nav>

<main class="content-wrapper" id="main-content">
  <h2 class="mb-4">Playlist Management</h2>
  <!-- Playlists Table -->
  <div class="card mb-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0" id="playlistTable">
          <thead class="thead-light">
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Associated Media (per Device)</th>
              <th>Schedule</th>
              <th>Total Duration</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($playlists) > 0): ?>
              <?php foreach ($playlists as $playlist): ?>
                <tr>
                  <td><?= htmlspecialchars($playlist['name']) ?></td>
                  <td><?= htmlspecialchars($playlist['description']) ?></td>
                  <td><?= htmlspecialchars($playlist['device_media_counts']) ?></td>
                  <td><?= htmlspecialchars($playlist['schedule']) ?></td>
                  <td><?= htmlspecialchars($playlist['duration']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center">No playlists found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Playlist Modal -->
  <button class="btn btn-success mb-3" data-toggle="modal" data-target="#playlistModal">New Playlist</button>
  <div class="modal fade" id="playlistModal" tabindex="-1" role="dialog" aria-labelledby="playlistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form class="modal-content" id="playlistForm" method="post" action="">
      <input type="hidden" name="action" value="save_playlist">
        <div class="modal-header">
          <h5 class="modal-title" id="playlistModalLabel">Create/Edit Playlist</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetPlaylistForm()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Playlist Info -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="playlistName">Name</label>
              <input type="text" class="form-control" id="playlistName" name="playlistName" required>
            </div>
            <div class="form-group col-md-6">
              <label for="playlistDescription">Description</label>
              <input type="text" class="form-control" id="playlistDescription" name="playlistDescription">
            </div>
          </div>
          <!-- Media Association -->
          <div class="form-group">
            <label>Associate Media (select and drag to reorder):</label>
            <ul id="mediaList" class="list-group mb-2">
              <?php while ($media = $media_files->fetch_assoc()): ?>
                <li class="list-group-item d-flex align-items-center" draggable="true" data-media-id="<?= $media['id'] ?>">
                  <input type="checkbox" class="mr-2 media-checkbox" name="media_files[]" value="<?= $media['id'] ?>">
                  <i class="fas fa-grip-vertical mr-2"></i>
                  <?= htmlspecialchars($media['file_name']) ?>
                  <span class="badge badge-info ml-auto"><?= htmlspecialchars($media['file_type']) ?></span>
                </li>
              <?php endwhile; ?>
            </ul>
            <small class="form-text text-muted">Check media to select. Drag and drop to change order.</small>
          </div>
          <!-- Device/Group Association -->
          <div class="form-group">
            <label for="playlistDevices">Associate with Devices/Groups</label>
            <select id="playlistDevices" name="playlistDevices[]" class="form-control" multiple>
              <?php while ($dev = $devices->fetch_assoc()): ?>
                <option value="<?= $dev['id'] ?>"><?= htmlspecialchars($dev['name']) ?></option>
              <?php endwhile; ?>
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
          </div>
          <!-- Schedule and Duration -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="playlistSchedule">Schedule (e.g. 08:00 - 12:00)</label>
              <input type="text" class="form-control" id="playlistSchedule" name="playlistSchedule" placeholder="08:00 - 12:00">
            </div>
            <div class="form-group col-md-6">
              <label for="playlistDuration">Total Duration (mm:ss)</label>
              <input type="text" class="form-control" id="playlistDuration" name="playlistDuration" placeholder="12:30">
            </div>
          </div>
          <input type="hidden" id="mediaOrder" name="media_order" value="">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Playlist</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetPlaylistForm()">Cancel</button>
        </div>
        <input type="hidden" name="action" value="save_playlist">
      </form>
    </div>
  </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  // Drag-and-drop for media list
  let draggedItem = null;
  document.addEventListener('DOMContentLoaded', function() {
    const list = document.getElementById('mediaList');
    if (list) {
      list.querySelectorAll('li').forEach(function(item) {
        item.addEventListener('dragstart', function(e) {
          draggedItem = item;
          setTimeout(() => item.style.display = 'none', 0);
        });
        item.addEventListener('dragend', function(e) {
          setTimeout(() => item.style.display = '', 0);
          draggedItem = null;
        });
        item.addEventListener('dragover', function(e) { e.preventDefault(); });
        item.addEventListener('drop', function(e) {
          e.preventDefault();
          if (draggedItem && draggedItem !== item) {
            list.insertBefore(draggedItem, item.nextSibling);
          }
        });
      });
    }
  });

  function resetPlaylistForm() {
    document.getElementById('playlistForm').reset();
    document.querySelectorAll('#mediaList input.media-checkbox').forEach(cb => cb.checked = false);
  }
</script>
</body>
</html>
