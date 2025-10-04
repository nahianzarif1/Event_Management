<?php
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "isd";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $securityque = $_POST["securityque"];
    $securityans = $_POST["securityans"];

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }

    do {
        $userID = rand(1000, 9999999);

        $result = $conn->query("SELECT userID FROM user WHERE userID = $userID");
    } while ($result && $result->num_rows > 0);

    $sql = "INSERT INTO user (userID, name, username, email, phone, password, securityque, securityans) 
            VALUES ($userID, '$name', '$username', '$email', '$phone', '$password', '$securityque', '$securityans')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green; text-align:center;'>Registration successful! Your user ID: $userID</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>Error: " . $conn->error . "</p>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="text" name="phone" placeholder="Phone Number" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="text" name="securityque" placeholder="Security Question" required><br>
            <input type="text" name="securityans" placeholder="Answer" required><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
