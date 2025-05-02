<?php
// media_blob.php
$conn = new mysqli("localhost", "root", "", "test2");
if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection error");
}

if (!isset($_GET['file_name'])) {
    http_response_code(400);
    exit("Missing file name");
}

$file_name = $conn->real_escape_string($_GET['file_name']);
$sql = "SELECT file_type, file_content FROM media_files WHERE file_name = '$file_name'";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    header("Content-Type: " . $row['file_type']);
    echo $row['file_content'];
} else {
    http_response_code(404);
    exit("File not found");
}
$conn->close();
?>
