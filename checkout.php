<?php
include 'dashboard.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = 'localhost';
$db = 'isd';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch userID from session username
$username = $_SESSION["username"];
$userID = null;
$stmt = $conn->prepare("SELECT userID FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userID);
$stmt->fetch();
$stmt->close();

if (!$userID) {
    die("User not found.");
}

// Get cart items from session
$cartItems = isset($_SESSION["cart"]) && !empty($_SESSION["cart"]) ? $_SESSION["cart"] : [];

if (empty($cartItems)) {
    die("Your cart is empty.");
}

$itemsData = [];
$total = 0;

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

// Function to generate unique random ID for a given table and column
function generateUniqueRandomID($conn, $table, $column) {
    do {
        $randomID = random_int(100000, 999999);
        $checkStmt = $conn->prepare("SELECT $column FROM $table WHERE $column = ?");
        $checkStmt->bind_param("i", $randomID);
        $checkStmt->execute();
        $checkStmt->store_result();
        $exists = $checkStmt->num_rows > 0;
        $checkStmt->close();
    } while ($exists);
    return $randomID;
}

// Handle form submission
$errors = [];
$success = "";

// Fix: Only validate payment method if form submitted and payment_method is set
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['payment_method'])) {
        $paymentMethod = $_POST['payment_method'];
        $validMethods = ['Credit_card', 'Paypal', 'Bank_transfer', 'Cash'];

        if (!in_array($paymentMethod, $validMethods)) {
            $errors[] = "Invalid payment method selected.";
        }
    } else {
        $errors[] = "Payment method is required.";
    }

    if (empty($errors)) {
        if ($paymentMethod !== 'Cash') {
            // Save necessary data in session and redirect to payment page
            $_SESSION['checkout_data'] = [
                'userID' => $userID,
                'total' => $total,
                'itemsData' => $itemsData,
                'paymentMethod' => $paymentMethod
            ];
            header("Location: payment.php");
            exit();
        } else {
            // Handle Cash payment immediately
            $conn->begin_transaction();

            try {
                // Generate unique random orderID
                $orderID = generateUniqueRandomID($conn, "orders", "orderID");

                // Insert into orders with generated orderID
                $stmt = $conn->prepare("INSERT INTO orders (orderID, userID, total_amount, status) VALUES (?, ?, ?, 'Pending')");
                $stmt->bind_param("iid", $orderID, $userID, $total);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert order: " . $stmt->error);
                }
                $stmt->close();

                // Insert order items with random order_item ID for each
                $stmt = $conn->prepare("INSERT INTO order_item (orderItemID, orderID, itemID, quantity, price) VALUES (?, ?, ?, ?, ?)");
                foreach ($itemsData as $item) {
                    $orderItemID = generateUniqueRandomID($conn, "order_item", "orderItemID");
                    $stmt->bind_param("iiiid", $orderItemID, $orderID, $item['itemID'], $item['quantity'], $item['price']);
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to insert order item: " . $stmt->error);
                    }
                }
                $stmt->close();

                // Generate unique random paymentID
                $paymentID = generateUniqueRandomID($conn, "payment", "paymentID");

                // Insert payment record with random paymentID
                $stmt = $conn->prepare("INSERT INTO payment (paymentID, orderID, amount, method, status) VALUES (?, ?, ?, ?, 'Pending')");
                $stmt->bind_param("iids", $paymentID, $orderID, $total, $paymentMethod);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert payment: " . $stmt->error);
                }
                $stmt->close();

                // Commit transaction
                $conn->commit();

                // Clear cart
                unset($_SESSION['cart']);

                $success = "Order placed successfully! Your order ID is #" . $orderID;

            } catch (Exception $e) {
                $conn->rollback();
                $errors[] = "Error placing order: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Checkout</title>
    <link rel="stylesheet" href="dashboard.css" />
    <link rel="stylesheet" href="checkout.css" />
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>
        <a href="shop.php" class="btn">Continue Shopping</a>
    <?php else: ?>

    <h3>Order Summary</h3>
    <table class="order-summary">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itemsData as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align:right"><strong>Total:</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </tbody>
    </table>

    <form method="post" action="checkout.php" class="checkout-form">
        <label for="payment_method">Select Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="" disabled selected>-- Choose a payment method --</option>
            <option value="Credit_card">Credit Card</option>
            <option value="Paypal">Paypal</option>
            <option value="Bank_transfer">Bank Transfer</option>
            <option value="Cash">Cash on Delivery</option>
        </select>

        <button type="submit" class="place-order-btn">Place Order</button>
    </form>

    <?php endif; ?>
</div>

</body>
</html>
