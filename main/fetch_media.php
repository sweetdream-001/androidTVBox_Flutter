<?php
// Database connection (adjust credentials as needed)
$conn = new mysqli("localhost", "root", "", "test2");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch media records
$sql = "SELECT file_name, file_type, client, file_path FROM media_files";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($row['file_name'])."</td>";
        echo "<td>".htmlspecialchars($row['file_type'])."</td>";
        echo "<td>".htmlspecialchars($row['client'])."</td>";
        echo "<td>".htmlspecialchars("constant")."</td>";
        echo "<td>".htmlspecialchars("constant")."</td>";
        echo "<td class='table-actions'>
                <button class='btn btn-info btn-sm' title='Preview' onclick=\"previewMedia('" . htmlspecialchars($row['file_name'], ENT_QUOTES) . "', '" . htmlspecialchars($row['file_type'], ENT_QUOTES) . "')\"><i class='fas fa-eye'></i></button>
                <button class='btn btn-primary btn-sm' title='Edit Metadata'><i class='fas fa-edit'></i></button>
                <button class='btn btn-danger btn-sm' title='Delete'><i class='fas fa-trash'></i></button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No media found.</td></tr>";
}
$conn->close();

?> 
<script>
        function previewMedia(fileName, fileType) {
      const previewBody = document.getElementById('mediaPreviewBody');
      let content = '';
      const url = 'media_blob.php?file_name=' + encodeURIComponent(fileName);

      if (fileType.startsWith('image/')) {
        content = `<img src="${url}" alt="Image Preview" class="media-preview img-fluid" />`;
      } else if (fileType.startsWith('video/')) {
        content = `<video controls class="media-preview" style="max-width:100%;max-height:400px;">
                    <source src="${url}" type="${fileType}">
                    Your browser does not support the video tag.
                  </video>`;
      } else if (fileType === 'application/pdf') {
        content = `<iframe src="${url}" class="media-preview" style="width:100%;height:400px;" frameborder="0"></iframe>`;
      } else {
        content = `<p>Preview not supported for this file type.</p>`;
      }

      previewBody.innerHTML = content;
      $('#mediaPreviewModal').modal('show');
    }

</script>