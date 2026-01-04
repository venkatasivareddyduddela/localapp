<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Funny Shirts Collection</title>
<style>
  body { font-family: Arial, sans-serif; background: #f0f0f0; text-align: center; margin: 0;}
  h1 { margin: 20px 0;}
  .search-area { display: flex; justify-content: center; gap: 10px; margin-bottom: 20px;}
  .search-area input, .search-area button { padding: 10px; font-size: 14px; border-radius: 6px; border: 1px solid #ccc;}
  .search-area button { background: #007bff; color: white; border:none; cursor:pointer;}
  form { margin-bottom: 20px;}
  .gallery { display:grid; grid-template-columns: repeat(4,1fr); gap:20px; max-width:1200px; margin:auto;}
  .card { background:white; padding:10px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
  .card img { width:100%; height:250px; object-fit:contain; background:#fafafa; border-radius:6px; cursor:pointer;}
  .info { margin-top:8px; font-weight:bold; font-size:14px;}
  .order-btn { margin-top:10px; width:100%; padding:8px; border:none; border-radius:6px; background:#28a745; color:white; cursor:pointer;}
</style>
</head>
<body>

<h1>kishore Shirts Collection</h1>

<!-- Upload form -->
<form action="upload_kshirts.php" method="POST" enctype="multipart/form-data">
  Shirt ID: <input type="text" name="shirt_id" required>
  Size: <input type="text" name="size" required>
  Price: <input type="number" name="price" required>
  About: <input type="text" name="about" required>
  Image: <input type="file" name="image" required>
  <button type="submit">Upload Shirt</button>
</form>

<div class="search-area">
  <input type="text" id="searchInput" placeholder="Search by Shirt ID">
  <button id="searchBtn">Search</button>
  <button id="showAllBtn">Show All</button>
</div>

<div class="gallery" id="gallery"></div>

<script>
let allShirts = [];

async function loadShirts() {
  const res = await fetch('fetch_kshirts.php');
  allShirts = await res.json();
  displayShirts(allShirts);
}

function displayShirts(shirts) {
  const gallery = document.getElementById("gallery");
  gallery.innerHTML = "";
  if (shirts.length === 0) {
    gallery.innerHTML = "<p>No shirts available.</p>";
    return;
  }
  shirts.forEach(shirt => {
    const card = document.createElement("div");
    card.className = "card";
    card.id = `shirt-${shirt.dbId}`;

    const img = document.createElement("img");
    img.src = "uploads/" + shirt.image.split("/").pop(); // ✅ fixed path
    img.alt = "Shirt Image";

    // 👇 click-to-delete
    img.onclick = async () => {
      if (confirm("Delete Shirt ID: " + shirt.id + " ?")) {
        const res = await fetch("delete_kshirts.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ dbId: shirt.dbId })
        });
        const text = await res.text();
        console.log("Raw delete response:", text);
        try {
          const result = JSON.parse(text);
          if (result.success) {
            allShirts = allShirts.filter(s => s.dbId !== shirt.dbId);
            displayShirts(allShirts);
            alert("Shirt deleted successfully!");
          } else {
            alert(result.error || "Failed to delete shirt.");
          }
        } catch (e) {
          alert("Delete failed: invalid JSON response");
          console.error(e);
        }
      }
    };

    const info = document.createElement("div");
    info.className = "info";
    info.innerHTML = `
      <div>ID: ${shirt.id}</div>
      <div>Size: ${shirt.size}</div>
      <div>Cost: ₹${shirt.cost}</div>
      <div>About: ${shirt.about}</div>
    `;

    const btn = document.createElement("button");
    btn.className = "order-btn";
    btn.textContent = "Order Now";
    btn.onclick = () => alert("Ordered Shirt ID: " + shirt.id);

    card.append(img, info, btn);
    gallery.appendChild(card);
  });
}

// Search
document.getElementById('searchBtn').onclick = () => {
  const key = document.getElementById('searchInput').value.trim();
  if (!key) return;
  const found = allShirts.filter(s => s.id === key);
  displayShirts(found);
};

document.getElementById('showAllBtn').onclick = () => {
  document.getElementById('searchInput').value = "";
  displayShirts(allShirts);
};

// Initial load
loadShirts();
</script>

</body>
</html>
