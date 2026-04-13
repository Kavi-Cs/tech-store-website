<?php
// Session එක පටන් අරන් නැත්නම් පටන් ගන්නවා
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cart එකේ භාණ්ඩ ගණන ගන්නවා
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; background-color: #f8f9fa; }
        main { flex: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Tech Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="cart.php">Cart (<?php echo $cart_count; ?>)</a>
                </li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item ms-2">
                            <a class="nav-link text-info fw-bold" href="admin_dashboard.php">⚙️ Admin Panel</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item ms-3">
                        <span class="nav-link text-white">Hi, <strong><?php echo $_SESSION['user_name']; ?></strong></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-2"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>