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

// ✅ Base URL of your hosted images (change to your actual domain)
$baseUrl = "https://if0_40766733.infinityfreeapp.com/uploads/";  

// ✅ Query the sshirts table
$result = $conn->query("SELECT id, shirt_id, size, price, about, image FROM sshirts ORDER BY id DESC");

$sshirts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sshirts[] = [
            "dbId"  => (int)$row["id"],       // numeric primary key for deletion
            "id"    => $row["shirt_id"],      // your custom shirt identifier
            "size"  => $row["size"],
            "cost"  => (int)$row["price"],
            "about" => $row["about"],
            // ✅ prepend full hosted URL for WhatsApp preview
            "image" => $baseUrl . $row["image"]
        ];
    }
}

echo json_encode($sshirts);
$conn->close();
?>

