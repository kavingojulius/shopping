<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Sanitize and validate product ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('<div class="container py-5"><div class="alert alert-danger">Invalid product ID.</div><a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a></div>');
}

// Fetch product using prepared statement
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    die('<div class="container py-5"><div class="alert alert-danger">Product not found.</div><a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a></div>');
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'];
    $visible = isset($_POST['visible']) ? 1 : 0;

    // Update product using prepared statement
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, visible = ? WHERE id = ?");
    $stmt->bind_param("ssdii", $name, $description, $price, $visible, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error: ' . htmlspecialchars($stmt->error) . ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles for indigo theme */
        .btn-indigo { background-color: #4f46e5; border-color: #4f46e5; color: white; }
        .btn-indigo:hover { background-color: #4338ca; border-color: #4338ca; }
        .card-shadow { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06); }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Edit Product</h2>
                        <?php if ($message): ?>
                            <?php echo $message; ?>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter product description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="visible" name="visible" <?php echo $product['visible'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="visible">Visible</label>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-indigo">Update Product</button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>