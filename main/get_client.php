<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$cpf = mysqli_real_escape_string($conn, $_GET['cpf']);
$sql = "SELECT * FROM clients WHERE CPF='$cpf' LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode([]);
}
$conn->close();
?>
