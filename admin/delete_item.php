<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id=$id");

header("Location: dashboard.php");
?>
