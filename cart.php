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
