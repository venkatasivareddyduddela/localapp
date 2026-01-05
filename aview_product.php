<?php
include "db.php";

$shopSlug = $_GET['shop'] ?? "";
$filterCat = $_GET['cat'] ?? "";

$sql = "
    SELECT p.*
    FROM products p
    JOIN shops s ON p.shop_id = s.id
    WHERE s.slug = ?
";

if (!empty($filterCat)) {
    $sql .= " AND p.category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $shopSlug, $filterCat);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $shopSlug);
}

$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products — <?php echo strtoupper($shopSlug); ?></title>
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
            transition: background 0.3s;
        }

        .filter-buttons a:hover {
            background-color: #009977;
        }

        .filter-buttons .active {
            background-color: #006655;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #00cc99;
            color: white;
        }

        img {
            max-width: 80px;
            border-radius: 6px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            font-weight: bold;
            color: #006655;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Products — <?php echo strtoupper($shopSlug); ?></h2>

<div class="filter-buttons">
    <a href="aview_product.php?shop=<?php echo $shopSlug; ?>" class="<?php echo $filterCat == '' ? 'active' : ''; ?>">All</a>
    <a href="aview_product.php?shop=<?php echo $shopSlug; ?>&cat=shirts" class="<?php echo $filterCat == 'shirts' ? 'active' : ''; ?>">Shirts</a>
    <a href="aview_product.php?shop=<?php echo $shopSlug; ?>&cat=pants" class="<?php echo $filterCat == 'pants' ? 'active' : ''; ?>">Pants</a>
    <a href="aview_product.php?shop=<?php echo $shopSlug; ?>&cat=shorts" class="<?php echo $filterCat == 'shorts' ? 'active' : ''; ?>">Shorts</a>
</div>

<table>
    <tr>
        <th>#</th>
        <th>Category</th>
        <th>Short ID</th>
        <th>Image</th>
        <th>Action</th>
    </tr>

<?php while ($row = $res->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo ucfirst($row['category']); ?></td>
        <td><?php echo $row['short_id']; ?></td>
        <td><img src="<?php echo $row['image_path']; ?>"></td>
        <td>
            <a href="aedit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="adelete_product.php?id=<?php echo $row['id']; ?>&shop=<?php echo $shopSlug; ?>"
               onclick="return confirm('Delete this product?');">Delete</a>
        </td>
    </tr>
<?php endwhile; ?>

</table>

<a href="aindex.php" class="back-link">← Back to Admin Dashboard</a>

</body>
</html>

