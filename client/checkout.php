<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$conn->query("DELETE FROM cart WHERE client_id = $client_id");

echo "<h3>Thank you for your request! We will contact you shortly.</h3>";
echo "<a href='index.php'>Back to Shop</a>";
?>
