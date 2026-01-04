<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Funny Pants Collection</title>
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

<h1>Funny Pants Collection</h1>

<!-- Upload form -->
<form action="upload_spants.php" method="POST" enctype="multipart/form-data">
  Pant ID: <input type="text" name="pant_id" required>
  Size: <input type="text" name="size" required>
  Price: <input type="number" name="price" required>
  About: <input type="text" name="about" required>
  Image: <input type="file" name="image" required>
  <button type="submit">Upload Pant</button>
</form>

<div class="search-area">
  <input type="text" id="searchInput" placeholder="Search by Pant ID">
  <button id="searchBtn">Search</button>
  <button id="showAllBtn">Show All</button>
</div>

<div class="gallery" id="gallery"></div>

<script>
let allPants = [];

async function loadPants() {
  const res = await fetch('fetch_spants.php');
  allPants = await res.json();
  displayPants(allPants);
}

function displayPants(pants) {
  const gallery = document.getElementById("gallery");
  gallery.innerHTML = "";
  if (pants.length === 0) {
    gallery.innerHTML = "<p>No pants available.</p>";
    return;
  }
  pants.forEach(pant => {
    const card = document.createElement("div");
    card.className = "card";
    card.id = `pant-${pant.dbId}`;

    const img = document.createElement("img");
    img.src = "uploads/" + pant.image.split("/").pop(); // ✅ fixed path
    img.alt = "Pant Image";

    // 👇 click-to-delete
    img.onclick = async () => {
      if (confirm("Delete Pant ID: " + pant.id + " ?")) {
        const res = await fetch("delete_spants.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ dbId: pant.dbId })
        });
        const text = await res.text();
        console.log("Raw delete response:", text);
        try {
          const result = JSON.parse(text);
          if (result.success) {
            allPants = allPants.filter(p => p.dbId !== pant.dbId);
            displayPants(allPants);
            alert("Pant deleted successfully!");
          } else {
            alert(result.error || "Failed to delete pant.");
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
      <div>ID: ${pant.id}</div>
      <div>Size: ${pant.size}</div>
      <div>Cost: ₹${pant.cost}</div>
      <div>About: ${pant.about}</div>
    `;

    const btn = document.createElement("button");
    btn.className = "order-btn";
    btn.textContent = "Order Now";
    btn.onclick = () => alert("Ordered Pant ID: " + pant.id);

    card.append(img, info, btn);
    gallery.appendChild(card);
  });
}

// Search
document.getElementById('searchBtn').onclick = () => {
  const key = document.getElementById('searchInput').value.trim();
  if (!key) return;
  const found = allPants.filter(p => p.id === key);
  displayPants(found);
};

document.getElementById('showAllBtn').onclick = () => {
  document.getElementById('searchInput').value = "";
  displayPants(allPants);
};

// Initial load
loadPants();
</script>

</body>
</html>
