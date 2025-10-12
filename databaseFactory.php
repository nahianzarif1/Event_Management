<?php
class DatabaseFactory {
    private static $host = "localhost";
    private static $dbUsername = "root";
    private static $dbPassword = "";
    private static $dbName = "isd";

    public static function createConnection() {
        $conn = new mysqli(self::$host, self::$dbUsername, self::$dbPassword, self::$dbName, 3306);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}
?>