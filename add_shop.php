<?php
include "db.php";

// Choose the plain password you want for this shop
$plainPassword = "mypassword123";

// Hash it securely
$password = password_hash($plainPassword, PASSWORD_DEFAULT);

// Insert shop record
$sql = "INSERT INTO shops (name, slug, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $slug, $password);

$name = "Shop A";
$slug = "shop-a";

if ($stmt->execute()) {
    echo "Shop added successfully!";
} else {
    echo "Error: " . $conn->error;
}
?>
