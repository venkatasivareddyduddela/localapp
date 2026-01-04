<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-Type: application/json');

$conn = new mysqli("sql313.infinityfree.com", "if0_40766733", "60jRYjLWGRd8zon", "if0_40766733_fashionstore", 3306);
if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $pant_id = $_POST['pant_id'];
    $size    = $_POST['size'];
    $price   = $_POST['price'];
    $about   = $_POST['about'];

    // Upload image
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName   = time() . "_" . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $fileName;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        die(json_encode(["error" => "Failed to upload image!"]));
    }

    // ✅ Use placeholders instead of interpolating variables
    $sql = "INSERT INTO fpants (pant_id, size, price, about, image) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $conn->error]));
    }

    // Bind params: pant_id (string), size (string), price (int), about (string), image (string)
    $stmt->bind_param("ssiss", $pant_id, $size, $price, $about, $fileName);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Execute failed: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>

