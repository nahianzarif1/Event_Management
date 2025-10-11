<div class="profile-container">

    <h2>Your Profile</h2>

    <section class="user-info">
        <h3>User Information</h3>
        <table>
            <tr><th>Name:</th><td><?php echo htmlspecialchars($user['name']); ?></td></tr>
            <tr><th>Username:</th><td><?php echo htmlspecialchars($user['username']); ?></td></tr>
            <tr><th>Email:</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
            <tr><th>Phone:</th><td><?php echo htmlspecialchars($user['phone']); ?></td></tr>
        </table>
    </section>

    <section class="orders">
        <h3>Your Orders & Payments</h3>
        <?php if ($ordersResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Payment Date</th>
                        <th>Payment Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $ordersResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['orderID']; ?></td>
                            <td><?php echo $order['orderDate']; ?></td>
                            <td><?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['paymentDate'] ?? '-'; ?></td>
                            <td><?php echo isset($order['paymentAmount']) ? number_format($order['paymentAmount'], 2) : '-'; ?></td>
                            <td><?php echo $order['method'] ?? '-'; ?></td>
                            <td><?php echo $order['paymentStatus'] ?? '-'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no orders yet.</p>
        <?php endif; ?>
    </section>

    <section class="upcoming-events">
        <h3>Upcoming Events & Service Bookings</h3>
        <?php if ($eventsResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Type</th>
                        <th>Event Date</th>
                        <th>Location</th>
                        <th>Booking ID</th>
                        <th>Booking Date</th>
                        <th>Scheduled Date</th>
                        <th>Service ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $eventsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['eventName']); ?></td>
                            <td><?php echo htmlspecialchars($event['eventType']); ?></td>
                            <td><?php echo $event['eventDate']; ?></td>
                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                            <td><?php echo $event['bookingID'] ?? '-'; ?></td>
                            <td><?php echo $event['bookingDate'] ?? '-'; ?></td>
                            <td><?php echo $event['scheduledDate'] ?? '-'; ?></td>
                            <td><?php echo $event['serviceID'] ?? '-'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No upcoming events found.</p>
        <?php endif; ?>
    </section>

</div>