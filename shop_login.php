<?php
include "db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug     = $_POST['slug'];
    $password = $_POST['password'];
    $redirect = $_POST['redirect'];

    $sql = "SELECT * FROM shops WHERE slug = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $shop = $result->fetch_assoc();
    

    if ($shop && password_verify($password, $shop['password'])) {
        $_SESSION['shop_access'] = $slug;
        header("Location: " . $redirect . "?shop=" . $slug);
        exit;
    } else {
        $error = "Invalid password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Shop Login</title></head>
<body>
<h2>Enter Shop Password</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <input type="hidden" name="slug" value="<?php echo $_GET['shop']; ?>">
    <input type="hidden" name="redirect" value="<?php echo $_GET['redirect']; ?>">
    <label>Password: <input type="password" name="password"></label>
    <button type="submit">Enter</button>
</form>
</body>
</html>
