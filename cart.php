<?php
include 'dashboard.php';

// Redirect if user is not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Redirect if cart is empty or not set
$cartItems = isset($_SESSION["cart"]) && !empty($_SESSION["cart"]) ? $_SESSION["cart"] : [];

// Database connection
$host = 'localhost';
$db = 'isd';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$total = 0;
$itemsData = [];

if (!empty($cartItems)) {
    $ids = implode(",", array_keys($cartItems));
    $sql = "SELECT * FROM shop_item WHERE itemID IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $itemID = $row['itemID'];
        $quantity = $cartItems[$itemID];
        $subtotal = $quantity * $row['price'];
        $total += $subtotal;

        $itemsData[] = [
            'itemID' => $itemID,
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
?>

<!-- Additional Cart Content -->
<link rel="stylesheet" href="cart.css">
<link rel="stylesheet" href="dashboard.css">

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($itemsData)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itemsData as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        <td>
                            <form method="post" action="remove_from_cart.php">
                                <input type="hidden" name="itemID" value="<?php echo $item['itemID']; ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <form method="post" action="clear_cart.php" class="clear-form">
            <button type="submit" class="clear-btn">Clear Cart</button>
        </form>

        <form method="post" action="checkout.php" class="checkout-form">
            <button type="submit" class="checkout-btn">Proceed to Checkout</button>
        </form>
    <?php endif; ?>
</div>
