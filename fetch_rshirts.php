<?php
header('Content-Type: application/json');

$conn = new mysqli("sql313.infinityfree.com", "if0_40766733", "60jRYjLWGRd8zon", "if0_40766733_fashionstore",3306);
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// ✅ Query the fshirts table
$result = $conn->query("SELECT id, shirt_id, size, price, about, image FROM rshirts ORDER BY id DESC");

$rshirts = [];
while ($row = $result->fetch_assoc()) {
    $rshirts[] = [
        "dbId"  => (int)$row["id"],       // numeric primary key for deletion
        "id"    => $row["shirt_id"],      // your custom shirt identifier
        "size"  => $row["size"],
        "cost"  => (int)$row["price"],
        "about" => $row["about"],
        "image" => "uploads/" . $row["image"] // prepend uploads/ for display
    ];
}

$result = $conn->query("SELECT id, shirt_id, size, price, about, image FROM rshirts ORDER BY id DESC");
if (!$result) {
    echo json_encode([]);
    $conn->close();
    exit;
}

echo json_encode($rshirts);
$conn->close();
?>