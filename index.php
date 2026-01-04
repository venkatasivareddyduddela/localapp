<?php
// Show all errors during development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only proceed if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form data safely (avoid undefined index warnings)
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $branch   = $_POST['branch']   ?? '';

    // Validate required fields
    if (empty($username) || empty($password) || empty($branch)) {
        echo "Please fill in all fields!";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $host = "sql313.infinityfree.com";
    $dbUser = "if0_40766733";
    $dbPass = "60jRYjLWGRd8zon";
    $dbName = "if0_40766733_fashionstore";
    $port = 3306;

    $conn = new mysqli($host, $dbUser, $dbPass, $dbName, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind (safe against SQL injection)
    $sql = "INSERT INTO customers (username, password, branch) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $username, $hashedPassword, $branch);

    if ($stmt->execute()) {
        // Redirect to login page after successful registration
        header("Location: login.html");
        exit();
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    // If the form was not submitted via POST
    echo "Please submit the form first.";
}
?>
