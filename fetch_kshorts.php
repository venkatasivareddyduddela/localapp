<?php
header('Content-Type: application/json');

$conn = new mysqli("sql313.infinityfree.com", "if0_40766733", "60jRYjLWGRd8zon", "if0_40766733_fashionstore", 3306);
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// ✅ Query the fshorts table using correct column names
$result = $conn->query("SELECT idPrimary, short_id, size, price, about, image FROM kshorts ORDER BY idPrimary DESC");

$kshorts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $kshorts[] = [
            "dbId"  => (int)$row["idPrimary"],   // numeric primary key for deletion
            "id"    => $row["short_id"],         // your custom shorts identifier
            "size"  => $row["size"],
            "price" => (float)$row["price"],     // keep numeric
            "about" => $row["about"],
            "image" => "uploads/" . $row["image"] // prepend uploads/ for display
        ];
    }
}

echo json_encode($kshorts);
$conn->close();
?>