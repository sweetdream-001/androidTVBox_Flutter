<?php
class MediaController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $baseUrl = "http://localhost/main/"; // Your base path
    
        $sql = "SELECT file_type, file_path FROM media_files ORDER BY uploaded_at DESC";
        $result = $this->conn->query($sql);
    
        $mediaFiles = [];
    
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $mime = $row["file_type"];
                $type = str_starts_with($mime, "image/") ? "image" :
                        (str_starts_with($mime, "video/") ? "video" : "other");
    
                $mediaFiles[] = [
                    "type" => $type,
                    "url" => $baseUrl . $row["file_path"]
                ];
            }
        }
    
        echo json_encode($mediaFiles);
    }

    // Future: add create(), delete(), update() methods
}
