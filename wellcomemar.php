<?php
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

    select {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid var(--accent);
      background: rgba(0,0,0,0.4);
      color: var(--text);
    }

    .shop-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
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
      padding: 20px 10px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      transition: transform 0.3s;
    }

    .shop-card:hover {
      transform: scale(1.03);
    }

    .shop-card img {
      width: 100%;
      aspect-ratio: 4/3;
      object-fit: cover;
      border-radius: 8px;
      border: 2px solid var(--accent);
      margin-bottom: 12px;
    }

    .shop-card h3 {
      font-size: 16px;
      font-weight: 600;
      color: var(--accent);
      margin: 0;
      text-transform: uppercase;
    }

    a {
      text-decoration: none;
      color: inherit;
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
  <div class="section-title">Shops in <span id="selected-city">Markapuram</span></div>

  <select id="city-select">
    <option value="Markapuram">Markapuram</option>
    <option value="ypalem">Ongole</option>
    <option value="Dhornal">Guntur</option>
    <option value="tripuranthakam">Nellore</option>
  </select>

  <div class="shop-grid" id="shop-grid"></div>
</div>

<script>
  const shopsByCity = {
    Markapuram: [
      { name: "Funny", image: "funny1.jpg", link: "fcpage.html" },
      { name: "Siva", image: "siva.jpg", link: "scpage.html" },
      { name: "Ragavendra", image: "ragavendra.jpg", link: "rcpage.html" },
      { name: "Kishore", image: "kishore.jpg", link: "kcpage.html" }
    ],
    Ongole: [
      { name: "Men's Fashion", image: "mens.jpg", link: "#" },
      { name: "Trendy Wear", image: "trendy.jpg", link: "#" },
      { name: "Local Hub", image: "local.jpg", link: "#" },
      { name: "Fashion Fix", image: "fix.jpg", link: "#" }
    ],
    Guntur: [],
    Nellore: []
  };

  const shopGrid = document.getElementById("shop-grid");
  const citySelect = document.getElementById("city-select");
  const selectedCity = document.getElementById("selected-city");

  function renderShops(city) {
    selectedCity.textContent = city;
    shopGrid.innerHTML = "";

    const shops = shopsByCity[city];
    if (!shops || shops.length === 0) {
      shopGrid.innerHTML = "<p style='color:white;'>No shops available in this city.</p>";
      return;
    }

    shops.forEach(shop => {
      const card = document.createElement("a");
      card.href = shop.link;
      card.innerHTML = `
        <div class="shop-card">
          <img src="${shop.image}" alt="${shop.name}" onerror="this.src='placeholder.jpg';">
          <h3>${shop.name}</h3>
        </div>
      `;
      shopGrid.appendChild(card);
    });
  }

  citySelect.addEventListener("change", () => {
    renderShops(citySelect.value);
  });

  // Initial render
  renderShops("Markapuram");
</script>

</body>
</html>


