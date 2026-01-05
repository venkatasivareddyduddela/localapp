<?php
include "db.php";
$slug = $_GET['slug'] ?? "";
$stmt = $conn->prepare("SELECT * FROM shops WHERE slug=?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$shop = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $shop['name']; ?> | Categories</title>
<style>
.cat { display:block; margin: 10px; padding: 15px; border:1px solid #ccc; width:200px;}
</style>
</head>
<body>

<h2>Welcome to <?php echo $shop['name']; ?> Fashions</h2>

<a class="cat" href="products.php?shop=<?php echo $slug;?>&cat=shirts">Shirts</a>
<a class="cat" href="products.php?shop=<?php echo $slug;?>&cat=pants">Pants</a>
<a class="cat" href="products.php?shop=<?php echo $slug;?>&cat=shorts">Shorts</a>

</body>
</html>
