<?php
header('Content-Type: application/json');

$conn = new mysqli(
    "sql313.infinityfree.com",
    "if0_40766733",
    "60jRYjLWGRd8zon",
    "if0_40766733_fashionstore",
    3306
);

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// ✅ Query the kshirts table
$result = $conn->query("SELECT idPrimary, shirt_id, size, price, about, image FROM kshirts ORDER BY idPrimary DESC");

$kshirts = [];
while ($row = $result->fetch_assoc()) {
    $kshirts[] = [
        "dbId"  => (int)$row["idPrimary"],   // correct primary key
        "id"    => $row["shirt_id"],        // custom shirt identifier
        "size"  => $row["size"],
        "cost"  => (int)$row["price"],      // rename price → cost
        "about" => $row["about"],
        "image" => "uploads/" . $row["image"] // prepend uploads/ for display
    ];
}

if (!$result) {
    echo json_encode([]);
    $conn->close();
    exit;
}

echo json_encode($kshirts);
$conn->close();
?>
