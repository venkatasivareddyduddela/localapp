<?php
// Enable error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to MySQL (make sure port matches your MySQL server)
$conn = new mysqli("localhost", "root", "", "fashionstore", 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data safely
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$branch   = isset($_POST['branch']) ? trim($_POST['branch']) : '';

// Validate required fields
if (empty($username) || empty($password) || empty($branch)) {
    echo "<script>alert('All fields are required!'); window.location.href='register.html';</script>";
    exit;
}

// Check if username already exists
$check = $conn->prepare("SELECT id FROM customers WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Username already exists!'); window.location.href='register.html';</script>";
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO customers (username, password, branch) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashedPassword, $branch);

if ($stmt->execute()) {
    echo "<script>alert('Successfully Registered!'); window.location.href='login.html';</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='register.html';</script>";
}

$stmt->close();
$conn->close();
?>
