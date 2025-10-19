<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="services.php">Services</a>
        <a href="profile.php">Profile</a>
        <a href="cart.php">Cart</a>
        <div class="username">
            Welcome, <?php echo htmlspecialchars($username); ?> |
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
        <a href="admin.php">Admin</a>
    </div>

</body>
</html>
