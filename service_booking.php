<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// DB Connection
$host = 'localhost';
$db = 'isd';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$serviceID = (int)$_POST['serviceID'];
$duration = (int)$_POST['duration'];
$guests = (int)$_POST['guests'];
$budget = $_POST['budget'];
$eventDate = $_POST['eventDate'];
$location = $conn->real_escape_string($_POST['location']);

// Fetch userID using username
$userQuery = $conn->prepare("SELECT userID FROM user WHERE username = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows === 0) {
    die("User not found.");
}

$userID = $userResult->fetch_assoc()['userID'];

// Generate random IDs
$eventID = rand(100000, 999999);
$bookingID = rand(100000, 999999);
$assignmentID = rand(100000, 999999);

// Generate event name & type based on service
$serviceQuery = $conn->prepare("SELECT name, category FROM service WHERE serviceID = ?");
$serviceQuery->bind_param("i", $serviceID);
$serviceQuery->execute();
$serviceResult = $serviceQuery->get_result();

if ($serviceResult->num_rows === 0) {
    die("Service not found.");
}

$serviceData = $serviceResult->fetch_assoc();
$eventName = $serviceData['name'] . " Booking";
$eventType = $serviceData['category'];

// 1. Insert into event table
$eventStmt = $conn->prepare("INSERT INTO event (eventID, userID, eventName, eventType, eventDate, location) VALUES (?, ?, ?, ?, ?, ?)");
$eventStmt->bind_param("iissss", $eventID, $userID, $eventName, $eventType, $eventDate, $location);

if (!$eventStmt->execute()) {
    die("Error inserting event: " . $eventStmt->error);
}

// 2. Insert into service_booking table
$bookingStmt = $conn->prepare("INSERT INTO service_booking (bookingID, userID, eventID, scheduledDate, serviceID) VALUES (?, ?, ?, ?, ?)");
$bookingStmt->bind_param("iiisi", $bookingID, $userID, $eventID, $eventDate, $serviceID);

if (!$bookingStmt->execute()) {
    die("Error inserting booking: " . $bookingStmt->error);
}

// 3. Assign a random staff
$staffResult = $conn->query("SELECT staffID FROM staff ORDER BY RAND() LIMIT 1");

if ($staffResult->num_rows === 0) {
    die("No available staff.");
}

$staffID = $staffResult->fetch_assoc()['staffID'];

// 4. Insert into staff_assignment
$assignStmt = $conn->prepare("INSERT INTO staff_assignment (assignmentID, bookingID, staffID) VALUES (?, ?, ?)");
$assignStmt->bind_param("iii", $assignmentID, $bookingID, $staffID);

if (!$assignStmt->execute()) {
    die("Error inserting staff assignment: " . $assignStmt->error);
}

// Success
$conn->close();
header("Location: booking_success.php?bookingID=$bookingID");
exit();
?>
