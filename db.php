<?php
// db.php
$servername = "sql313.infinityfree.com";
$username   = "if0_40766733";
$password   = "60jRYjLWGRd8zon";
$database   = "if0_40766733_fashionstore";
$port       = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
