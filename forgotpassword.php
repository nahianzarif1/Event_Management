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