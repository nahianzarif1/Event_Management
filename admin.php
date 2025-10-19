<?php
session_start();

// --- DATABASE CONFIG ---
$host = "localhost";
$dbname = "isd";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// --- AUTH ---
$adminUsername = "rubayet";
$adminPassword = "2107073admin";

if (isset($_POST['login'])) {
    if ($_POST['username'] === $adminUsername && $_POST['password'] === $adminPassword) {
        $_SESSION['admin'] = true;
    } else {
        $error = "Invalid username or password!";
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// --- ALLOWED TABLES ---
$allowedTables = [
    'user','event','shop_item','order','order_item',
    'payment','service','service_booking','staff','service_staff_assignment'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="container">
<?php if (!isset($_SESSION['admin'])): ?>
    <!-- Login Form -->
    <form method="POST" class="login-form">
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

<?php else: ?>
    <!-- Admin Panel -->
    <div class="admin-panel">
        <h2>Welcome, Admin</h2>
        <form method="POST"><button type="submit" name="logout" class="logout-btn">Logout</button></form>

        <!-- Dropdown Menu -->
        <form method="GET" class="dropdown">
            <label for="action">Choose action:</label>
            <select name="action" id="action" required>
                <option value="">-- Select Action --</option>
                <option value="view">View Table</option>
                <option value="add">Add Record</option>
                <option value="delete">Delete Record</option>
            </select>

            <label for="table">Choose table:</label>
            <select name="table" id="table" required>
                <option value="">-- Select Table --</option>
                <?php foreach ($allowedTables as $tbl): ?>
                    <option value="<?= $tbl ?>"><?= ucfirst(str_replace("_", " ", $tbl)) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Go</button>
        </form>

        <?php
        $action = $_GET['action'] ?? '';
        $table = $_GET['table'] ?? '';

        if ($action && $table && in_array($table, $allowedTables)) {
            echo "<hr>";
            echo "<h3>" . strtoupper($action) . " - " . strtoupper($table) . "</h3>";

            if ($action === "view") {
                $result = $conn->query("SELECT * FROM `$table`");
                if ($result && $result->num_rows > 0) {
                    echo "<table border='1' cellpadding='5'><tr>";
                    while ($field = $result->fetch_field()) {
                        echo "<th>" . htmlspecialchars($field->name) . "</th>";
                    }
                    echo "</tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $val) {
                            echo "<td>" . htmlspecialchars($val) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No records found in `$table`.</p>";
                }

            } elseif ($action === "add") {
                if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_submit'])) {
                    $cols = [];
                    $vals = [];
                    foreach ($_POST as $key => $val) {
                        if ($key === 'add_submit') continue;
                        $cols[] = "`" . $conn->real_escape_string($key) . "`";
                        $vals[] = "'" . $conn->real_escape_string($val) . "'";
                    }
                    if ($cols) {
                        $sql = "INSERT INTO `$table` (" . implode(",", $cols) . ") VALUES (" . implode(",", $vals) . ")";
                        if ($conn->query($sql)) {
                            echo "<p class='success'>Record added successfully.</p>";
                        } else {
                            echo "<p class='error'>Error: " . $conn->error . "</p>";
                        }
                    }
                }

                $cols = $conn->query("SHOW COLUMNS FROM `$table`");
                echo "<form method='POST'>";
                while ($col = $cols->fetch_assoc()) {
                    if ($col['Extra'] === 'auto_increment') continue;
                    echo "<label>" . $col['Field'] . ": <input name='" . $col['Field'] . "' required></label><br>";
                }
                echo "<button type='submit' name='add_submit'>Add</button></form>";

            } elseif ($action === "delete") {
                $pkResult = $conn->query("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
                if ($pkResult->num_rows === 0) {
                    echo "<p class='error'>No primary key found. Cannot delete from `$table`.</p>";
                } else {
                    $pkCol = $pkResult->fetch_assoc()['Column_name'];

                    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete_id'])) {
                        $delId = $conn->real_escape_string($_POST['delete_id']);
                        $sql = "DELETE FROM `$table` WHERE `$pkCol` = '$delId' LIMIT 1";
                        if ($conn->query($sql)) {
                            echo "<p class='success'>Record deleted successfully.</p>";
                        } else {
                            echo "<p class='error'>Error: " . $conn->error . "</p>";
                        }
                    }

                    echo "<form method='POST'>";
                    echo "<label>Enter $pkCol to delete: <input name='delete_id' required></label><br>";
                    echo "<button type='submit'>Delete</button></form>";
                }
            }
        }
        ?>
    </div>
<?php endif; ?>
</div>
</body>
</html>
