<?php
session_start();
include '../config/db.php';

// Check if client is logged in
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Handle add to cart from redirect or direct POST
$show_toast = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    // Add to cart
    $stmt = $conn->prepare("INSERT INTO cart (client_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $client_id, $product_id);
    $stmt->execute();
    $show_toast = true;
} elseif (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    // Validate product_id
    if (is_numeric($product_id)) {
        $stmt = $conn->prepare("INSERT INTO cart (client_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $client_id, $product_id);
        $stmt->execute();
        $show_toast = true;
    }
}

// Fetch cart items
$sql = "SELECT products.name, products.price, cart.quantity, cart.product_id 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Welcome to the Shop</h1>
        
        <div class="text-center mb-5">
            <a href="../index.php" class="btn btn-secondary">Back to Home</a>
        </div>

        <!-- Toast Notification for Adding to Cart -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="cartToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Item added to cart!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <h2 class="mb-4">Your Cart</h2>
        <?php if ($result->num_rows > 0) { ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            $subtotal = $row['price'] * $row['quantity'];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td>$<?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="fw-bold">$<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        <?php } else { ?>
            <p class="text-center">Your cart is empty.</p>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php if ($show_toast) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toastEl = document.getElementById('cartToast');
                const toast = new bootstrap.Toast(toastEl, {
                    delay: 3000 // Toast disappears after 3 seconds
                });
                toast.show();
            });
        </script>
    <?php } ?>
</body>
</html>