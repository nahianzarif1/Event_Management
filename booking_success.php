<?php
if (!isset($_GET['bookingID'])) {
    header("Location: services.php");
    exit();
}

$bookingID = (int)$_GET['bookingID'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="confirmation">
        <h1>Booking Successful!</h1>
        <p>Your booking ID is <strong>#<?php echo $bookingID; ?></strong></p>
        <a href="services.php" class="btn">Book Another Service</a>
    </div>
</body>
</html>
