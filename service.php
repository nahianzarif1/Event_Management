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

// Search & Pagination
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 5;
$offset = ($page - 1) * $itemsPerPage;

$whereClause = "";
if (!empty($search)) {
    $whereClause = "WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
}

$sqlCount = "SELECT COUNT(*) AS total FROM service $whereClause";
$resultCount = $conn->query($sqlCount);
$totalItems = $resultCount->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$sql = "SELECT * FROM service $whereClause LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="services.css">

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

<script>
// Popup logic including guest & budget in quotation

function openBookingPopup(cardElem) {
    const modal = document.getElementById("bookingModal");
    const serviceID = cardElem.getAttribute("data-service-id");
    const serviceName = cardElem.getAttribute("data-service-name");
    const basePrice = parseFloat(cardElem.getAttribute("data-base-price"));
    const baseDuration = parseInt(cardElem.getAttribute("data-base-duration"));

    document.getElementById("modalServiceName").innerText = "Book: " + serviceName;
    document.getElementById("modalServiceID").value = serviceID;

    document.getElementById("inputDuration").value = baseDuration;
    document.getElementById("inputGuests").value = 1;
    document.getElementById("budgetSelect").value = "simple";
    document.getElementById("inputDate").value = "";
    document.getElementById("inputLocation").value = "";

    // Initial quotation
    updateQuotation();

    // Attach change handlers
    document.getElementById("inputDuration").oninput = updateQuotation;
    document.getElementById("inputGuests").oninput = updateQuotation;
    document.getElementById("budgetSelect").onchange = updateQuotation;

    modal.style.display = "block";

    function updateQuotation() {
        let dur = parseInt(document.getElementById("inputDuration").value) || baseDuration;
        let guests = parseInt(document.getElementById("inputGuests").value) || 1;
        let budget = document.getElementById("budgetSelect").value;

        // Base price per minute
        let unitPrice = basePrice / baseDuration;
        let price = unitPrice * dur;

        // Adjust for budget category multipliers
        let multiplier = 1;
        if (budget === "premium") multiplier = 1.5;
        else if (budget === "royal") multiplier = 2;

        price = price * multiplier;

        // Maybe charge extra per guest beyond a base threshold
        // For simplicity: price * (1 + (guests - 1) * 0.1)
        if (guests > 1) {
            price = price * (1 + (guests - 1) * 0.10);
        }

        document.getElementById("quotationAmount").innerText = price.toFixed(2);
    }
}

function closeBookingPopup() {
    const modal = document.getElementById("bookingModal");
    modal.style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("bookingModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
</script>

