<?php
session_start();

// වැදගත්ම ආරක්ෂිත පියවර:
// කවුරුහරි ලොග් වෙලා නැත්නම්, හෝ ලොග් වෙලා ඉන්න කෙනා 'admin' නෙවෙයි නම් කෙලින්ම මුල් පිටුවට පන්නනවා.
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include('includes/header.php');
?>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <h2 class="mb-4 text-primary fw-bold">Admin Dashboard</h2>
    
    <div class="alert alert-success fs-5 shadow-sm">
        👋 Welcome back, Admin <strong><?php echo $_SESSION['user_name']; ?></strong>!
    </div>

    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm text-center border-0 bg-light h-100">
                <div class="card-body py-5">
                    <h3 class="card-title text-dark">📦 Manage Products</h3>
                    <p class="card-text text-muted">Add new products to the store, or edit/delete existing ones.</p>
                    <a href="admin_products.php" class="btn btn-primary btn-lg mt-3 px-4 shadow-sm">Go to Products</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm text-center border-0 bg-light h-100">
                <div class="card-body py-5">
                    <h3 class="card-title text-dark">🛒 Customer Orders</h3>
                    <p class="card-text text-muted">View all online customer orders, their items, and total payments.</p>
                    <a href="admin_orders.php" class="btn btn-success btn-lg mt-3 px-4 shadow-sm">View Orders</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm text-center border-0 bg-light h-100">
                <div class="card-body py-5">
                    <h3 class="card-title text-dark">📊 Manage Stock</h3>
                    <p class="card-text text-muted">Check current inventory levels, update stock, and view low stock alerts.</p>
                    <a href="admin_stock.php" class="btn btn-warning text-dark btn-lg mt-3 px-4 shadow-sm fw-bold">Check Inventory</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm text-center border-0 bg-light h-100">
                <div class="card-body py-5">
                    <h3 class="card-title text-dark">🏪 POS System</h3>
                    <p class="card-text text-muted">Fast in-store billing system for walk-in customers.</p>
                    <a href="admin_pos.php" class="btn btn-info text-dark btn-lg mt-3 px-4 shadow-sm fw-bold">Open POS</a>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card shadow-sm text-center border-0 bg-light h-100">
                <div class="card-body py-4">
                    <h3 class="card-title text-dark">📈 Sales & Revenue Reports</h3>
                    <p class="card-text text-muted">View today's sales, monthly revenue, and overall transaction history.</p>
                    <a href="admin_sales.php" class="btn btn-danger text-white btn-lg mt-2 px-5 shadow-sm fw-bold">View Sales Report</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>