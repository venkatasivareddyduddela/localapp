<?php
include "db.php";

$shopSlug = $_GET['shop'] ?? "";

// Get products for this shop
$sql = "SELECT p.* FROM products p JOIN shops s ON p.shop_id = s.id WHERE s.slug = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $shopSlug);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo ucfirst($shopSlug); ?> Collection</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f8ff;
      margin: 0;
      padding: 20px;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    .filter-buttons {
      text-align: center;
      margin-bottom: 20px;
    }
    .filter-buttons a {
      display: inline-block;
      margin: 6px;
      padding: 8px 14px;
      background-color: #00cc99;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .filter-buttons a:hover {
      background-color: #009977;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }
    /* Force 2 cards per row on mobile */
    @media (max-width: 600px) {
      .product-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    .product-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
      padding: 12px;
    }
    .product-card img {
      max-width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 6px;
      margin-bottom: 10px;
    }
    .product-card h3 {
      margin: 0;
      font-size: 16px;
      color: #333;
    }
    .product-card p {
      margin: 4px 0;
      color: #666;
    }
    .order-btn {
      display: inline-block;
      margin-top: 8px;
      padding: 6px 12px;
      background: #00cc99;
      color: #fff;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }
    .order-btn:hover {
      background: #009977;
    }
  </style>
</head>
<body>

<h2><?php echo ucfirst($shopSlug); ?> Collection</h2>

<!-- Category Filters -->
<div class="filter-buttons">
  <a href="shop_collection.php?shop=<?php echo $shopSlug; ?>">All</a>
  <a href="shop_collection.php?shop=<?php echo $shopSlug; ?>&cat=shirts">Shirts</a>
  <a href="shop_collection.php?shop=<?php echo $shopSlug; ?>&cat=pants">Pants</a>
  <a href="shop_collection.php?shop=<?php echo $shopSlug; ?>&cat=shorts">Shorts</a>
</div>

<div class="product-grid">
<?php
$filterCat = $_GET['cat'] ?? "";
if (!empty($filterCat)) {
    $sql = "SELECT p.* FROM products p JOIN shops s ON p.shop_id = s.id WHERE s.slug = ? AND p.category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $shopSlug, $filterCat);
    $stmt->execute();
    $res = $stmt->get_result();
}

while ($row = $res->fetch_assoc()):
?>
  <div class="product-card">
    <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>">
    <h3><?php echo $row['name']; ?></h3>
    <p>Category: <?php echo ucfirst($row['category']); ?></p>
    <p>Price: ₹<?php echo $row['price']; ?></p>
    <a href="order.php?id=<?php echo $row['id']; ?>" class="order-btn">Order Now</a>
  </div>
<?php endwhile; ?>
</div>

</body>
</html>



