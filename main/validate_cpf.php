<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cpf = mysqli_real_escape_string($conn, $_GET['cpf']);
$sql = "SELECT COUNT(*) FROM clients WHERE CPF='$cpf'";
$result = $conn->query($sql);
$row = $result->fetch_row();
$count = $row[0];

if ($count == 0) {
    echo "true"; // CPF is unique
} else {
    echo "false"; // CPF is NOT unique
}
$conn->close();
?>
