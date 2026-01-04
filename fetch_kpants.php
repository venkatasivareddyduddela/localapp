<?php
header('Content-Type: application/json');

$conn = new mysqli("sql313.infinityfree.com", "if0_40766733", "60jRYjLWGRd8zon", "if0_40766733_fashionstore", 3306);
if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$sql = "SELECT id, pant_id, size, price, about, image FROM kpants ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$kpants = [];
while ($row = $result->fetch_assoc()) {
    $kpants[] = [
        "dbId"  => (int)$row["id"],
        "id"    => $row["pant_id"],
        "size"  => $row["size"],
        "price" => $row["price"],   // keep consistent naming
        "about" => $row["about"],
        "image" => "uploads/" . $row["image"]
    ];
}

echo json_encode($kpants);
$conn->close();
?>
