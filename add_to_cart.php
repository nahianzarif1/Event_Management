<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemID = $_POST["itemID"];
    $quantity = $_POST["quantity"];

    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    if (isset($_SESSION["cart"][$itemID])) {
        $_SESSION["cart"][$itemID] += $quantity;
    } else {
        $_SESSION["cart"][$itemID] = $quantity;
    }

    header("Location: shop.php");
    exit();
}
?>
