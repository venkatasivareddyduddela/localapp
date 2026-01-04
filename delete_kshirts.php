<?php
header('Content-Type: application/json');
$conn = new mysqli("sql313.infinityfree.com", "if0_40766733", "60jRYjLWGRd8zon", "if0_40766733_fashionstore", 3306);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["dbId"])) {
    echo json_encode(["success" => false, "error" => "Missing dbId"]);
    exit;
}

$dbId = (int)$data["dbId"]; // ✅ this is idPrimary

$stmt = $conn->prepare("DELETE FROM kshirts WHERE idPrimary = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $dbId); // ✅ integer binding
$ok = $stmt->execute();
$stmt->close();

echo json_encode(["success" => $ok, "deletedId" => $dbId]);
$conn->close();
?>


