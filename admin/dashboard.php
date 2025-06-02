<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch products
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles to mimic Tailwind's indigo and spacing */
        .bg-indigo-700 { background-color: #4f46e5; }
        .bg-indigo-800 { background-color: #4338ca; }
        .bg-indigo-600 { background-color: #5b21b6; }
        .bg-indigo-100 { background-color: #e0e7ff; }
        .text-indigo-600 { color: #4f46e5; }
        .hover-bg-indigo-600:hover { background-color: #5b21b6; }
        .text-sm { font-size: 0.875rem; }
        .rounded-full { border-radius: 9999px; }
        .shadow { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06); }
        .sidebar-link.active { background-color: #4338ca; }
        .sidebar-link:hover { background-color: #5b21b6; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex vh-100">
        <!-- Sidebar -->
        <div class="d-none d-md-flex flex-column flex-shrink-0 bg-indigo-700 text-white" style="width: 16rem;">
            <div class="d-flex align-items-center justify-content-center h4 p-3 bg-indigo-800">
                <span class="fw-semibold">Admin Panel</span>
            </div>
            <div class="flex-grow-1 px-3 py-4 overflow-y-auto">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link sidebar-link active text-white rounded py-3">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="add_item.php" class="nav-link sidebar-link text-white rounded py-3">
                            <i class="fas fa-plus-circle me-2"></i>
                            Add Product
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../logout.php" class="nav-link sidebar-link text-white rounded py-3">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Mobile Sidebar (Offcanvas) -->
        <div class="d-md-none">
            <div class="offcanvas offcanvas-start text-white" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="background-color: #4f46e5;">
                <div class="offcanvas-header bg-indigo-800">
                    <h5 class="offcanvas-title text-white" id="mobileSidebarLabel">Admin Panel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link sidebar-link active text-white rounded py-3">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add_item.php" class="nav-link sidebar-link text-white rounded py-3">
                                <i class="fas fa-plus-circle me-2"></i>
                                Add Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../logout.php" class="nav-link sidebar-link text-white rounded py-3">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1 d-flex flex-column overflow-hidden">
            <!-- Top navigation -->
            <header class="d-flex align-items-center justify-content-between p-3 bg-white border-bottom">
                <div class="d-flex align-items-center">
                    <button class="d-md-none btn btn-outline-secondary me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="input-group w-auto">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Search products..." style="max-width: 200px;">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <button class="btn d-flex align-items-center" type="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="rounded-circle me-2" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Admin profile" width="32" height="32">
                            <span class="d-none d-md-inline text-sm">Admin</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-grow-1 overflow-y-auto p-4">
                <div class="mb-4">
                    <h1 class="h2 fw-bold text-dark">Admin Dashboard</h1>
                    <p class="text-muted">Welcome, Admin! Manage your products below.</p>
                </div>

                <!-- Products Table -->
                <div class="card shadow">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Products</h2>
                        <a href="add_item.php" class="btn btn-primary btn-sm">Add New Product</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td>$<?php echo number_format($row['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($row['description'] ?? 'No description'); ?></td>
                                        <td class="text-end">
                                            <a href="edit_item.php?id=<?php echo $row['id']; ?>" class="text-primary me-2">Edit</a>
                                            <a href="delete_item.php?id=<?php echo $row['id']; ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if ($result->num_rows === 0): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No products found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>