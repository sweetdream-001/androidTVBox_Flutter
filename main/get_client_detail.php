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
    echo "<h5>" . htmlspecialchars($row["FullName"]) . "</h5>";
    echo "<p>";
    echo "<strong>CPF:</strong> " . htmlspecialchars($row["CPF"]) . "<br>";
    echo "<strong>Address:</strong> " . htmlspecialchars($row["Street"]) . ", " . htmlspecialchars($row["StreetNumber"]) . ", " . htmlspecialchars($row["Complement"]) . ", " . htmlspecialchars($row["Neighborhood"]) . ", " . htmlspecialchars($row["City"]) . ", " . htmlspecialchars($row["State"]) . ", " . htmlspecialchars($row["ZipCode"]) . "<br>";
    echo "<strong>Segment:</strong> " . htmlspecialchars($row["MarketSegment"]) . "<br>";
    echo "<strong>Lat/Lng:</strong> " . htmlspecialchars($row["Latitude"]) . ", " . htmlspecialchars($row["Longitude"]) . "<br>";
    echo "</p>";
} else {
    echo "Client not found";
}
$conn->close();
?>
