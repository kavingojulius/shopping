<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['client_id'])) {
    echo "Please <a href='login.php'>login</a> to add to cart.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $client_id = $_SESSION['client_id'];
    $product_id = $_POST['product_id'];

    // Add to cart
    $conn->query("INSERT INTO cart (client_id, product_id, quantity) VALUES ($client_id, $product_id, 1)");
    echo "Added to cart. <a href='cart.php'>View Cart</a>";
    exit();
}

// Show cart items
$client_id = $_SESSION['client_id'];
$sql = "SELECT products.name, products.price, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.client_id = $client_id";

$result = $conn->query($sql);
$total = 0;

echo "<h2>Your Cart</h2>";
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    echo "{$row['name']} - \${$row['price']} x {$row['quantity']} = \$$subtotal<br>";
}
echo "<strong>Total: \$$total</strong><br><br>";
echo "<a href='checkout.php'>Proceed to Checkout</a>";
?>
