<?php
session_start();
include 'config/db.php';

$sql = "SELECT * FROM products WHERE visible = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to My Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Welcome to the Shopping Website</h1>
        
        <div class="text-center mb-5">
            <p class="lead">Are you an admin or a client?</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <?php if (!isset($_SESSION['admin_id'])) { ?>
                    <a href="admin/login.php" class="btn btn-primary">Admin Login</a>
                    <a href="client/login.php" class="btn btn-success">Client Login</a>
                    <a href="client/register.php" class="btn btn-info">Client Register</a>
                    <a href="client/index.php" class="btn btn-secondary">Visit Shop</a>
                <?php } else { ?>
                    <a href="admin/dashboard.php" class="btn btn-primary">Dashboard</a>
                <?php } ?>

                                
                
                <?php if ( isset($_SESSION['client_id']) || isset($_SESSION['admin_id'])  ){ ?>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                <?php } ?>
            </div>
        </div>

        <!-- Toast Notification for Logged-in Users -->
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

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-text fw-bold">Price: $<?php echo number_format($row['price'], 2); ?></p>
                            <?php if (isset($_SESSION['client_id'])) { ?>
                                <form method="post" action="client/index.php">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-outline-primary w-100 mb-2 add-to-cart-btn">Add to Cart</button>
                                </form>
                            <?php } else { ?>
                                <a href="client/login.php?product_id=<?php echo $row['id']; ?>" class="btn btn-outline-primary w-100 mb-2">Add to Cart</a>
                            <?php } ?>
                            <p class="card-text"><a href="client/login.php" class="text-decoration-none">Login</a> to add to cart</p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            const toastEl = document.getElementById('cartToast');
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000 // Toast disappears after 3 seconds
            });

            addToCartButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    toast.show();
                });
            });
        });
    </script>
</body>
</html>


