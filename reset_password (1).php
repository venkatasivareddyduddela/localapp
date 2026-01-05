<?php
include "db.php";

// Function to update a shop password
function updateShopPassword($conn, $slug, $plainPassword) {
    $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE shops SET password=? WHERE slug=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed, $slug);

    if ($stmt->execute()) {
        echo "Password updated for $slug!<br>";
    } else {
        echo "Error updating $slug: " . $conn->error . "<br>";
    }

    $stmt->close();
}

// Update each shop separately
updateShopPassword($conn, "funny", "secret123");
updateShopPassword($conn, "siva", "secret12");
updateShopPassword($conn, "ragavendra", "secret13");
updateShopPassword($conn, "kishore", "secret14");
?>
