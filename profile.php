<?php
session_start();

// If user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --accent: #00ff88;
      --text: #fff;
      --bg: rgba(0,0,0,0.7);
      --sidebar-bg: #111;
    }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #222;
      color: var(--text);
    }
    header {
      background: var(--bg);
      padding: 12px 18px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Profile icon */
    .profile-btn {
      font-size: 24px;
      cursor: pointer;
      color: var(--accent);
    }

    .logout-btn {
      background: var(--accent);
      border: none;
      padding: 8px 12px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      color: #000;
    }

    /* Sidebar */
    #sidebar {
      position: fixed;
      left: -300px;
      top: 0;
      width: 260px;
      height: 100%;
      background: var(--sidebar-bg);
      padding: 20px;
      transition: 0.3s ease-in-out;
      box-shadow: 2px 0 12px rgba(0,0,0,0.6);
    }

    #sidebar.active {
      left: 0;
    }

    #sidebar .close-btn {
      font-size: 20px;
      cursor: pointer;
      color: var(--accent);
      margin-bottom: 20px;
    }

    #sidebar h3 {
      color: var(--accent);
      margin-bottom: 20px;
      font-size: 18px;
    }

    #sidebar a {
      display: block;
      text-decoration: none;
      color: var(--text);
      margin: 12px 0;
      font-size: 16px;
      padding: 8px;
      border-radius: 4px;
    }
    #sidebar a:hover {
      background: var(--accent);
      color: #000;
    }

    /* Order Sections */
    .orders-section {
      padding: 20px;
      display: none;
      background: #111;
      margin: 20px;
      border-radius: 8px;
    }
    .orders-section.active {
      display: block;
    }
  </style>
</head>
<body>

<header>
  <div>
    <i class="fa-solid fa-user-circle profile-btn" onclick="toggleSidebar()"></i>
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
  </div>
  <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
</header>

<!-- Sidebar -->
<aside id="sidebar">
  <i class="fa-solid fa-times close-btn" onclick="toggleSidebar()"></i>
  <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
  <a href="#" onclick="showSection('pending')">Pending Orders</a>
  <a href="#" onclick="showSection('completed')">Completed Orders</a>
</aside>

<!-- Main Content -->
<div class="orders-section" id="pending">
  <h2>Pending Orders</h2>
  <?php
  // Fetch pending orders from DB
  $conn = new mysqli("sql313.infinityfree.com","if0_40766733","60jRYjLWGRd8zon","if0_40766733_fashionstore",3306);
  $sql = "SELECT * FROM orders WHERE username = ? AND status = 'pending' ORDER BY id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $_SESSION['username']);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows == 0) {
    echo "<p>No pending orders.</p>";
  } else {
    while($row = $result->fetch_assoc()){
      echo "<div><strong>Order #{$row['id']}</strong> — {$row['item']} x {$row['quantity']}</div>";
    }
  }
  ?>
</div>

<div class="orders-section" id="completed">
  <h2>Completed Orders</h2>
  <?php
  // Fetch completed orders
  $sql = "SELECT * FROM orders WHERE username = ? AND status = 'completed' ORDER BY id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $_SESSION['username']);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows == 0) {
    echo "<p>No completed orders.</p>";
  } else {
    while($row = $result->fetch_assoc()){
      echo "<div><strong>Order #{$row['id']}</strong> — {$row['item']} x {$row['quantity']}</div>";
    }
  }
  $conn->close();
  ?>
</div>

<script>
  // Toggle sidebar open/close
  function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
  }

  // Show specific section
  function showSection(sec) {
    document.getElementById("pending").classList.remove("active");
    document.getElementById("completed").classList.remove("active");

    document.getElementById(sec).classList.add("active");
    toggleSidebar(); // auto-close sidebar
  }
</script>

</body>
</html>
