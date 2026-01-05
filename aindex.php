<?php
// show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "db.php";
session_start();

// If user is not logged in, redirect back to login
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title>Fashions of Markapuram</title>

  <!-- Font Awesome CDN for icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  >

  <style>
    :root {
      --accent: #00ff88;
      --card-bg: rgba(255,255,255,0.08);
      --text: #ffffff;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('https://img.freepik.com/premium-vector/background-images-free-download-freepik_689361-61.jpg?w=740') center/cover no-repeat fixed;
      min-height: 100vh;
      color: var(--text);
    }

    header {
      background: rgba(0,0,0,0.6);
      padding: 16px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-sizing: border-box;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .profile-icon {
      font-size: 26px;
      color: var(--accent);
    }

    header h2 {
      font-size: 20px;
      font-weight: 600;
      margin: 0;
      display: inline-flex;
      align-items: center;
    }

    .logout-btn {
      background-color: var(--accent);
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      color: #000;
      font-weight: bold;
    }

    .container {
      padding: 24px 16px;
      max-width: 1000px;
      margin: auto;
    }

    .section-title {
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 20px;
      color: var(--accent);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .shop-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 30px;
    }

    @media (min-width: 768px) {
      .shop-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (min-width: 1024px) {
      .shop-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    .shop-card {
      background: var(--card-bg);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      transition: transform 0.3s;
      height: 180px; /* 👈 makes the box taller */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .shop-card:hover {
      transform: scale(1.03);
    }

    .shop-card h3 {
      font-size: 20px;
      font-weight: 700;
      color: var(--accent);
      margin: 0;
      text-transform: uppercase;
    }

    .shop-actions {
      text-align: center;
      margin-top: 12px;
    }

    .shop-actions a {
      display: inline-block;
      margin: 6px;
      padding: 8px 12px;
      background: var(--accent);
      color: #000;
      border-radius: 6px;
      font-size: 14px;
      font-weight: bold;
      text-decoration: none;
      transition: background 0.3s;
    }

    .shop-actions a:hover {
      background: #00cc66;
    }
  </style>
</head>
<body>

<header>
  <div class="profile-info">
    <a href="profile.php" class="profile-link">
      <i class="fa-solid fa-user-circle profile-icon"></i>
    </a>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
  </div>
  <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
</header>

<div class="container">
  <div class="section-title">Admin Dashboard — Shops</div>

  <div class="shop-grid">
    <?php
    $sql = "SELECT * FROM shops";
    $result = $conn->query($sql);

    if (!$result) {
        echo "<p>Error fetching shops: " . $conn->error . "</p>";
    } else {
        while ($row = $result->fetch_assoc()):
    ?>
      <div>
        <div class="shop-card">
          <h3><?php echo strtoupper($row['name']); ?></h3>
        </div>
        <div class="shop-actions">
          <a href="shop_login.php?shop=<?php echo $row['slug']; ?>&redirect=aupload.php">➕ Add Product</a>
          <a href="shop_login.php?shop=<?php echo $row['slug']; ?>&redirect=aview_product.php">📦 View Products</a>
        </div>
      </div>
    <?php endwhile; } ?>
  </div>
</div>

</body>
</html>


