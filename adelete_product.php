<?php
include "db.php";
$id = $_GET['id'];
$shopSlug = $_GET['shop'] ?? "";

// Delete image file
$res = $conn->query("SELECT image_path FROM products WHERE id=$id");
$row = $res->fetch_assoc();
if (file_exists($row['image_path'])) {
    unlink($row['image_path']);
}

// Delete record
$conn->query("DELETE FROM products WHERE id=$id");

header("Location: aview_product.php?shop=".$shopSlug);
exit;
?>
