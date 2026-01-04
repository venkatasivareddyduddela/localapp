<?php
session_start();

// Database connection
$conn = new mysqli("sql313.infinityfree.com", "if0_40766733", "60jRYjLWGRd8zon", "if0_40766733_fashionstore", 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$branch   = $_POST['branch'] ?? '';
$branch   = strtolower(trim($branch));

// --- Owner Login Check (hardcoded) ---
if ($username === "funny" && $password === "7569456416" && $branch === "markapur") {
    $_SESSION['username'] = $username;
    header("Location: ownerpage.html");
    exit;
}

if ($username === "siva" && $password === "8688155673" && $branch === "markapur") {
    $_SESSION['username'] = $username;
    header("Location: sivapage.html");
    exit;
}

if ($username === "ragavendra" && $password === "6300008043" && $branch === "markapur") {
    $_SESSION['username'] = $username;
    header("Location: ragapage.html");
    exit;
}

if ($username === "kishore" && $password === "6304021218" && $branch === "markapur") {
    $_SESSION['username'] = $username;
    header("Location: kishpage.html");
    exit;
}

// --- Customer Login Check ---
$stmt = $conn->prepare("SELECT password, branch FROM customers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedBranch = strtolower(trim($row['branch']));

    if (password_verify($password, $row['password'])) {
        if (strcasecmp($branch, $storedBranch) === 0) {
            $_SESSION['username'] = $username; // ✅ store username
            header("Location: wellcomemar.php");   // redirect to welcome page
            exit;
        } else {
            echo "<script>alert('Invalid Branch!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid Password!'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('User not found!'); window.location.href='login.html';</script>";
}

$stmt->close();
$conn->close();
?>


