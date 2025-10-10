<div class="services-container">

    <form method="get" action="services.php" class="search-form">
        <input type="text" name="search" placeholder="Search by name or category"
               value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <div class="service-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="service-card"
                     data-service-id="<?php echo $row['serviceID']; ?>"
                     data-service-name="<?php echo htmlspecialchars($row['name']); ?>"
                     data-base-price="<?php echo $row['price']; ?>"
                     data-base-duration="<?php echo $row['duration']; ?>"
                     onclick="openBookingPopup(this)">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                    <p>Duration: <?php echo $row['duration']; ?> mins</p>
                    <p>Base Price: $<?php echo number_format($row['price'], 2); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-results">No services found.</p>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="<?php echo $i == $page ? 'active' : ''; ?>"
                   href="services.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Booking Popup Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeBookingPopup()">&times;</span>
        <h2 id="modalServiceName">Book Service</h2>

        <form id="bookingForm" method="post" action="service_booking.php">
            <input type="hidden" name="serviceID" id="modalServiceID">

            <div class="form-group">
                <label for="inputDuration">Duration (minutes):</label>
                <input type="number" name="duration" id="inputDuration" min="1" required>
            </div>

            <div class="form-group">
                <label for="inputGuests">Number of Guests:</label>
                <input type="number" name="guests" id="inputGuests" min="1" required>
            </div>

            <div class="form-group">
                <label for="budgetSelect">Budget Category:</label>
                <select name="budget" id="budgetSelect" required>
                    <option value="simple">Simple</option>
                    <option value="premium">Premium</option>
                    <option value="royal">Royal</option>
                </select>
            </div>

            <div class="form-group">
                <label for="inputDate">Date & Time:</label>
                <input type="datetime-local" name="eventDate" id="inputDate" required>
            </div>

            <div class="form-group">
                <label for="inputLocation">Location:</label>
                <input type="text" name="location" id="inputLocation" required>
            </div>

            <div class="form-group">
                <label>Quotation: $<span id="quotationAmount">0.00</span></label>
            </div>

            <button type="submit" class="btn-book">Confirm Booking</button>
        </form>
    </div>
</div>
