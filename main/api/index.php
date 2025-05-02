
<?php
header("Access-Control-Allow-Origin:*");
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
// Connect to DB
$conn = new mysqli("localhost", "root", "", "test2");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Autoload or include controller
require_once 'MediaController.php';
require_once 'ExternalAPIController.php';
$mediaController = new MediaController($conn);
$ExternalAPIController = new ExternalAPIController($conn);

// Parse route
$request = $_GET['request'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'media-files':
        if ($method === 'GET') {
            $mediaController->getAll();
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
        }
        break;

    case 'news':
        $news = $ExternalAPIController->getNews();
        echo json_encode($news);
        break;

    case 'weather-ads':
        $ads = $ExternalAPIController->generateWeatherAds();
        echo json_encode($ads);
        break;
    
    default:
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found"]);
}

$conn->close();
