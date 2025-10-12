<?php
include 'dashboard.php'; 

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Database connection
$host = 'localhost';
$db = 'isd';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search and Pagination
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 5;
$offset = ($page - 1) * $itemsPerPage;

// Search query
$whereClause = "";
if (!empty($search)) {
    $whereClause = "WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
}

$sqlCount = "SELECT COUNT(*) AS total FROM shop_item $whereClause";
$resultCount = $conn->query($sqlCount);
$totalItems = $resultCount->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$sql = "SELECT * FROM shop_item $whereClause LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!-- Shop Page Styles -->
<link rel="stylesheet" href="shop.css">
<link rel="stylesheet" href="dashboard.css">

<div class="shop-container">

    <!-- Search bar -->
    <form method="get" action="shop.php" class="search-form">
        <input type="text" name="search" placeholder="Search by name or category" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Items listing -->
    <div class="item-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="item-card">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                    <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                    <p>Stock: <?php echo $row['stock']; ?></p>

                    <form method="post" action="add_to_cart.php" class="add-cart-form">
                        <input type="hidden" name="itemID" value="<?php echo $row['itemID']; ?>">
                        <input type="number" name="quantity" min="1" max="<?php echo $row['stock']; ?>" value="1">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-results">No items found.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="<?php echo $i == $page ? 'active' : ''; ?>" href="shop.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>

</div>
