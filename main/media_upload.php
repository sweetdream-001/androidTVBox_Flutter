<?php
// Database config
$host = 'localhost';
$db   = 'test2';
$user = 'root';
$pass = '';

// Connect to database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define upload directory
$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true); // Create uploads folder if not exists
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['mediaFile']) && $_FILES['mediaFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['mediaFile']['tmp_name'];
        $fileName = basename($_FILES['mediaFile']['name']);
        $fileSize = $_FILES['mediaFile']['size'];
        $fileType = $_FILES['mediaFile']['type'];
        $client = $conn->real_escape_string($_POST['advertisingClient']);

        // Limit file size to 50MB
        if ($fileSize > 50 * 1024 * 1024) {
            die("File too large. Max 50MB allowed.");
        }

        // Create unique file name
        $uniqueFileName = uniqid('media_', true) . '_' . $fileName;
        $destPath = $uploadDir . $uniqueFileName;

        // Move uploaded file to server folder
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $relativePath = 'uploads/' . $uniqueFileName;

            // Insert metadata into database
            $stmt = $conn->prepare("INSERT INTO media_files (file_name, file_type, file_size, client, file_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $fileName, $fileType, $fileSize, $client, $relativePath);

            if ($stmt->execute()) {
                echo "<script> window.location.href='media_management.php';</script>";
            } else {
                echo "<script>alert('Database error: " . $stmt->error . "'); window.history.back();</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Failed to move uploaded file.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No file uploaded or upload error!'); window.history.back();</script>";
    }
}

$conn->close();
?>
