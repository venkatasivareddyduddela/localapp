<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

$dbId = (int)$data["dbId"];

$stmt = $conn->prepare("DELETE FROM spants WHERE id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

$stmt->bind_param("i", $dbId);
$ok = $stmt->execute();
$stmt->close();

echo json_encode(["success" => $ok, "deletedId" => $dbId]);
$conn->close();
?>
