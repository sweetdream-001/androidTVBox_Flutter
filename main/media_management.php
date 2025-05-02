<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <title>Media Management Module</title>
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
      margin-top: 5rem;
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
      top: 56px;
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
      padding: 76px 24px 24px 24px;
      min-height: 100vh;
      transition: all 0.3s;
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
    /* Media Management Styles */
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
    .table-actions .btn {
      margin-right: 0.25rem;
    }
    .modal-lg {
      max-width: 900px;
    }
    .media-preview {
      max-width: 100%;
      max-height: 400px;
      display: block;
      margin: 0 auto;
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
        <a class="nav-link active" href="media_management.php"><i class="fas fa-photo-video"></i> Media Management</a>
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
      <h2><i class="fas fa-photo-video"></i> Media Management</h2>
      <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#uploadModal" onclick="resetUploadForm()">
        <i class="fas fa-upload"></i> Upload Media
      </button>
    </div>

    <!-- Filters -->
    <form id="filterForm" class="form-inline mb-3">
      <div class="form-group mr-3">
        <label for="filterType" class="mr-2">Type</label>
        <select id="filterType" class="form-control">
          <option value="">All Types</option>
          <option>Image</option>
          <option>Video</option>
          <option>PDF</option>
        </select>
      </div>
      <div class="form-group mr-3">
        <label for="filterClient" class="mr-2">Advertising Client</label>
        <select id="filterClient" class="form-control">
          <option value="">All Clients</option>
          <option>Client A</option>
          <option>Client B</option>
          <option>Client C</option>
        </select>
      </div>
      <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply</button>
      <button type="button" class="btn btn-secondary ml-2" onclick="resetFilters()">Reset</button>
    </form>

    <!-- Media List Table -->
    <div class="card">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0" id="mediaTable">
            <thead class="thead-light">
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Advertising Client</th>
                <th>Duration</th>
                <th>Dimensions</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="mediaTableBody">
              <?php include 'fetch_media.php'; ?>
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

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
      <form class="modal-content" id="uploadForm" method="post" action="media_upload.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadModalLabel">Upload Media</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetUploadForm()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="mediaFile">Select File (Images, Videos, PDFs)</label>
            <input type="file" class="form-control-file" id="mediaFile" name="mediaFile" accept="image/*,video/*,application/pdf" required>
            <small class="form-text text-muted">Max size: 50MB</small>
          </div>
          <div class="form-group">
            <label for="advertisingClient">Advertising Client</label>
            <select id="advertisingClient" name="advertisingClient" class="form-control" required>
              <option value="">Select client</option>
              <option>Client A</option>
              <option>Client B</option>
              <option>Client C</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Upload</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetUploadForm()">Cancel</button>
        </div>
      </form>

      </div>
    </div>

    <!-- Edit Metadata Modal -->
    <div class="modal fade" id="editMetadataModal" tabindex="-1" role="dialog" aria-labelledby="editMetadataModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="editMetadataForm" onsubmit="return saveMetadata(event)">
          <div class="modal-header">
            <h5 class="modal-title" id="editMetadataModalLabel">Edit Media Metadata</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetMetadataForm()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="mediaName">Name</label>
              <input type="text" id="mediaName" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="mediaDescription">Description</label>
              <textarea id="mediaDescription" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
              <label for="mediaDuration">Duration</label>
              <input type="text" id="mediaDuration" class="form-control" placeholder="e.g. 00:02:30">
            </div>
            <div class="form-group">
              <label for="mediaDimensions">Dimensions</label>
              <input type="text" id="mediaDimensions" class="form-control" placeholder="e.g. 1920x1080">
            </div>
            <div class="form-group">
              <label for="metadataClient">Advertising Client</label>
              <select id="metadataClient" class="form-control" required>
                <option value="">Select client</option>
                <option>Client A</option>
                <option>Client B</option>
                <option>Client C</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Metadata</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetMetadataForm()">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Media Preview Modal -->
    <div class="modal fade" id="mediaPreviewModal" tabindex="-1" role="dialog" aria-labelledby="mediaPreviewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mediaPreviewModalLabel">Media Preview</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetMediaPreview()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center" id="mediaPreviewBody">
            <!-- Media preview content inserted dynamically -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetMediaPreview()">Close</button>
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
    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('main-content').classList.toggle('shifted');
    });

    // Reset upload form
    function resetUploadForm() {
      document.getElementById('uploadForm').reset();
      document.getElementById('fileError').classList.add('d-none');
      document.getElementById('fileError').textContent = '';
    }

      event.preventDefault();
      const fileInput = document.getElementById('mediaFile');
      const file = fileInput.files[0];
      const errorDiv = document.getElementById('fileError');
      errorDiv.classList.add('d-none');
      errorDiv.textContent = '';

      if (!file) {
        errorDiv.textContent = 'Please select a file.';
        errorDiv.classList.remove('d-none');
        return false;
      }

      const allowedTypes = ['image/', 'video/', 'application/pdf'];
      const isValidType = allowedTypes.some(type => file.type.startsWith(type));
      if (!isValidType) {
        errorDiv.textContent = 'Invalid file type. Allowed: images, videos, PDFs.';
        errorDiv.classList.remove('d-none');
        return false;
      }

      const maxSizeMB = 50;
      if (file.size > maxSizeMB * 1024 * 1024) {
        errorDiv.textContent = `File size exceeds ${maxSizeMB}MB limit.`;
        errorDiv.classList.remove('d-none');
        return false;
      }

      // Proceed with upload (demo: just alert)
      alert('File validated and ready to upload (demo).');
      $('#uploadModal').modal('hide');
      resetUploadForm();
      return false;
    }

    // Open Edit Metadata modal and prefill
    function openEditMetadata(name, description, duration, dimensions, client) {
      document.getElementById('mediaName').value = name;
      document.getElementById('mediaDescription').value = description;
      document.getElementById('mediaDuration').value = duration;
      document.getElementById('mediaDimensions').value = dimensions;
      document.getElementById('metadataClient').value = client;
      $('#editMetadataModal').modal('show');
    }

    // Reset metadata form
    function resetMetadataForm() {
      document.getElementById('editMetadataForm').reset();
    }

    // Save metadata (demo)
    function saveMetadata(event) {
      event.preventDefault();
      alert('Metadata saved (demo).');
      $('#editMetadataModal').modal('hide');
      resetMetadataForm();
      return false;
    }

    // Open media preview modal
    function openMediaPreview(type, url) {
      const previewBody = document.getElementById('mediaPreviewBody');
      let content = '';

      if (type === 'image') {
        content = `<img src="${url}" alt="Image Preview" class="media-preview img-fluid rounded">`;
      } else if (type === 'video') {
        content = `
          <video controls class="media-preview rounded" style="max-width:100%; max-height:400px;">
            <source src="${url}" type="video/mp4">
            Your browser does not support the video tag.
          </video>`;
      } else if (type === 'pdf') {
        content = `<iframe src="${url}" class="media-preview" style="width:100%; height:400px;" frameborder="0"></iframe>`;
      } else {
        content = '<p>Preview not available.</p>';
      }

      previewBody.innerHTML = content;
      $('#mediaPreviewModal').modal('show');
    }

    // Reset media preview modal content
    function resetMediaPreview() {
      document.getElementById('mediaPreviewBody').innerHTML = '';
    }

    // Filters
    function applyFilters() {
      const typeFilter = document.getElementById('filterType').value.toLowerCase();
      const clientFilter = document.getElementById('filterClient').value.toLowerCase();

      const rows = document.querySelectorAll('#mediaTableBody tr');
      rows.forEach(row => {
        const type = row.cells[1].textContent.toLowerCase();
        const client = row.cells[2].textContent.toLowerCase();

        const show = 
          (typeFilter === '' || type.includes(typeFilter)) &&
          (clientFilter === '' || client.includes(clientFilter));

        row.style.display = show ? '' : 'none';
      });
    }

    function resetFilters() {
      document.getElementById('filterType').value = '';
      document.getElementById('filterClient').value = '';
      applyFilters();
    }

  </script>
</body>
</html>
