<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "db.php";

$shopSlug = $_GET['shop'] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category = $_POST['category'];
    $short_id = $_POST['short_id'];
    $size     = $_POST['size'];
    $price    = $_POST['price'];
    $about    = $_POST['about'];

    // SAFE FOLDER CREATION
    $baseDir = "uploads";
    if (!is_dir($baseDir)) mkdir($baseDir, 0777);

    $shopDir = "$baseDir/$shopSlug";
    if (!is_dir($shopDir)) mkdir($shopDir, 0777);

    $categoryDir = "$shopDir/$category";
    if (!is_dir($categoryDir)) mkdir($categoryDir, 0777);

    $imageName = time() . "_" . basename($_FILES["fileToUpload"]["name"]);
    $targetFile = "$categoryDir/$imageName";

    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        die("❌ Image upload failed");
    }

    $stmt = $conn->prepare("
        INSERT INTO products (shop_id, category, short_id, size, price, about, image_path)
        VALUES ((SELECT id FROM shops WHERE slug=?),?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "sssssss",
        $shopSlug,
        $category,
        $short_id,
        $size,
        $price,
        $about,
        $targetFile
    );

    if (!$stmt->execute()) {
        die("DB Error: " . $stmt->error);
    }

    // ✅ CORRECT REDIRECT
    header("Location: aview_product.php?shop=" . $shopSlug);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Upload Product — <?php echo strtoupper($shopSlug); ?></title></head>
<body>

<h2>Upload Product — <?php echo strtoupper($shopSlug); ?></h2>

<form method="POST" enctype="multipart/form-data">

<select name="category" required>
  <option value="shirts">Shirts</option>
  <option value="pants">Pants</option>
  <option value="shorts">Shorts</option>
</select><br><br>

Short ID:<br><input name="short_id" required><br><br>
Size:<br><input name="size" required><br><br>
Price:<br><input name="price" required><br><br>
About:<br><input name="about" required><br><br>
Image:<br><input type="file" name="fileToUpload" required><br><br>

<button type="submit">UPLOAD PRODUCT</button>
</form>

<br><a href="aindex.php">← Back</a>

</body>
</html>
