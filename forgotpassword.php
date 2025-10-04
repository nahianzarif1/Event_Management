<?php
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "isd";

$step = 1;
$securityQuestion = "";
$username = "";
$showChangeForm = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST["check_username"])) {
        $username = $_POST["username"];
        $stmt = $conn->prepare("SELECT securityque FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $securityQuestion = $row["securityque"];
            $step = 2;
        } else {
            $error = "Username not found.";
        }

        $stmt->close();
    }

    if (isset($_POST["check_answer"])) {
        $username = $_POST["username"];
        $answer = $_POST["securityans"];

        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND securityans = ?");
        $stmt->bind_param("ss", $username, $answer);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $showChangeForm = true;
        } else {
            $error = "Incorrect answer.";
            $step = 2;
        }

        $stmt->close();
    }

    if (isset($_POST["change_password"])) {
        $username = $_POST["username"];
        $newPassword = $_POST["newpassword"];

        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $newPassword, $username);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success = "Password changed successfully!";
        } else {
            $error = "Failed to update password.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgotpassword.css">
</head>
<body>
    <div class="forgot-container">
        <h2>Forgot Password</h2>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>

        <?php if ($step === 1): ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Enter your username" required>
                <button type="submit" name="check_username">Next</button>
            </form>

        <?php elseif ($step === 2): ?>
            <p><strong>Security Question:</strong> <?php echo $securityQuestion; ?></p>
            <form method="POST">
                <input type="hidden" name="username" value="<?php echo $username; ?>">
                <input type="text" name="securityans" placeholder="Answer" required>
                <button type="submit" name="check_answer">Submit Answer</button>
            </form>

        <?php endif; ?>

        <?php if ($showChangeForm): ?>
            <form method="POST">
                <input type="hidden" name="username" value="<?php echo $username; ?>">
                <input type="password" name="newpassword" placeholder="New Password" required>
                <button type="submit" name="change_password">Change Password</button>
            </form>
        <?php endif; ?>

        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
