<?php
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'test2';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM api_integrations ORDER BY id DESC");
    $apis = [];
    while ($row = $result->fetch_assoc()) {
        $apis[] = $row;
    }
    echo json_encode($apis);

} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON"]);
        exit;
    }

    $id = (int)($data['id'] ?? 0);
    $name = $conn->real_escape_string($data['name']);
    $url = $conn->real_escape_string($data['url']);
    $auth_key = $conn->real_escape_string($data['auth_key']);
    $status = (int)$data['status'];

    if ($id > 0) {
        // Update
        $stmt = $conn->prepare("UPDATE api_integrations SET name=?, url=?, auth_key=?, status=? WHERE id=?");
        $stmt->bind_param("sssii", $name, $url, $auth_key, $status, $id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO api_integrations (name, url, auth_key, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $url, $auth_key, $status);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();

} elseif ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
        $conn->query("DELETE FROM api_integrations WHERE id = $id");
        echo json_encode(["success" => true]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing or invalid ID"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}

$conn->close();
