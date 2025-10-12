<?php
include 'dashboard.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['checkout_data'])) {
    header("Location: checkout.php");
    exit();
}

$data = $_SESSION['checkout_data'];
$userID = $data['userID'];
$total = $data['total'];
$itemsData = $data['itemsData'];
$paymentMethod = $data['paymentMethod'];

// For security, you might want to revalidate these details here by querying DB

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate inputs based on payment method
    if ($paymentMethod === 'Credit_card') {
        $cardNumber = trim($_POST['card_number'] ?? '');
        $cardExpiry = trim($_POST['card_expiry'] ?? '');
        $cardCVV = trim($_POST['card_cvv'] ?? '');

        // Basic validation
        if (!$cardNumber || !$cardExpiry || !$cardCVV) {
            $errors[] = "Please fill in all card details.";
        } elseif (!preg_match('/^\d{16}$/', $cardNumber)) {
            $errors[] = "Card number must be 16 digits.";
        } elseif (!preg_match('/^\d{2}\/\d{2}$/', $cardExpiry)) {
            $errors[] = "Expiry date must be in MM/YY format.";
        } elseif (!preg_match('/^\d{3}$/', $cardCVV)) {
            $errors[] = "CVV must be 3 digits.";
        }
    } elseif ($paymentMethod === 'Paypal') {
        $paypalEmail = trim($_POST['paypal_email'] ?? '');
        if (!$paypalEmail || !filter_var($paypalEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please provide a valid PayPal email address.";
        }
    } elseif ($paymentMethod === 'Bank_transfer') {
        $bankRef = trim($_POST['bank_ref'] ?? '');
        if (!$bankRef) {
            $errors[] = "Please provide your bank transfer reference number.";
        }
    }

    if (empty($errors)) {
        $conn = new mysqli('localhost', 'root', '', 'isd');
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

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

        $conn->begin_transaction();

        try {
            $orderID = generateUniqueRandomID($conn, "orders", "orderID");

            // Insert order with status Pending
            $stmt = $conn->prepare("INSERT INTO orders (orderID, userID, total_amount, status) VALUES (?, ?, ?, 'Pending')");
            $stmt->bind_param("iid", $orderID, $userID, $total);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert order: " . $stmt->error);
            }
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO order_item (orderItemID, orderID, itemID, quantity, price) VALUES (?, ?, ?, ?, ?)");
            foreach ($itemsData as $item) {
                $orderItemID = generateUniqueRandomID($conn, "order_item", "orderItemID");
                $stmt->bind_param("iiiid", $orderItemID, $orderID, $item['itemID'], $item['quantity'], $item['price']);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert order item: " . $stmt->error);
                }
            }
            $stmt->close();

            $paymentID = generateUniqueRandomID($conn, "payment", "paymentID");

            // Insert payment with status Success
            $stmt = $conn->prepare("INSERT INTO payment (paymentID, orderID, amount, method, status) VALUES (?, ?, ?, ?, 'Success')");
            $stmt->bind_param("iids", $paymentID, $orderID, $total, $paymentMethod);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert payment: " . $stmt->error);
            }
            $stmt->close();

            $conn->commit();

            // Clear session cart and checkout data
            unset($_SESSION['cart']);
            unset($_SESSION['checkout_data']);

            $success = "Payment complete! Your order ID is #" . $orderID;

        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Error completing payment: " . $e->getMessage();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Complete Payment</title>
    <link rel="stylesheet" href="dashboard.css" />
    <link rel="stylesheet" href="payment.css" />
</head>
<body>

<div class="payment-container">
    <h2>Complete Your Payment</h2>

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

    <form method="post" action="payment.php" class="payment-form">
        <?php if ($paymentMethod === 'Credit_card'): ?>
            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" id="card_number" maxlength="16" placeholder="1234 5678 9012 3456" required />

            <label for="card_expiry">Expiry Date (MM/YY):</label>
            <input type="text" name="card_expiry" id="card_expiry" maxlength="5" placeholder="MM/YY" required />

            <label for="card_cvv">CVV:</label>
            <input type="text" name="card_cvv" id="card_cvv" maxlength="3" placeholder="123" required />

        <?php elseif ($paymentMethod === 'Paypal'): ?>
            <label for="paypal_email">PayPal Email:</label>
            <input type="email" name="paypal_email" id="paypal_email" placeholder="you@example.com" required />

        <?php elseif ($paymentMethod === 'Bank_transfer'): ?>
            <label for="bank_ref">Bank Transfer Reference Number:</label>
            <input type="text" name="bank_ref" id="bank_ref" placeholder="Enter your bank transfer reference" required />

        <?php else: ?>
            <p>You selected <strong><?php echo htmlspecialchars(str_replace('_', ' ', $paymentMethod)); ?></strong> as your payment method.</p>
            <p>Click "Complete Payment" to finalize your order.</p>
        <?php endif; ?>

        <button type="submit" class="complete-payment-btn">Complete Payment</button>
    </form>

    <?php endif; ?>
</div>

</body>
</html>
