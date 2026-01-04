
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-Type: application/json');

$conn = new mysqli("sql313.infinityfree.com", "if0_40766733", "60jRYjLWGRd8zon", "if0_40766733_fashionstore", 3306);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $short_id = $_POST['short_id'];
    $size     = $_POST['size'];
    $price    = $_POST['price'];
    $about    = $_POST['about'];

    // Upload image
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName   = time() . "_" . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $fileName;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        echo json_encode(["success" => false, "error" => "Failed to upload image!"]);
        exit;
    }

    // ✅ Insert into sshorts table
    $sql = "INSERT INTO sshorts (short_id, size, price, about, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
        exit;
    }

    // Bind params: short_id (string), size (string), price (double), about (string), image (string)
    $stmt->bind_param("ssdss", $short_id, $size, $price, $about, $fileName);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "id"      => $short_id,
            "size"    => $size,
            "price"   => $price,
            "about"   => $about,
            "image"   => "uploads/" . $fileName
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Execute failed: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
