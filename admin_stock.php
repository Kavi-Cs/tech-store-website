<?php
session_start();
require('includes/db_connect.php');
include('includes/header.php');

// Stock එක අප්ඩේට් කරන බොත්තම එබුවාම වෙන දේ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock_btn'])) {
    $p_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $new_stock = mysqli_real_escape_string($conn, $_POST['new_stock']);

    // Database එකේ Stock එක අලුත් කරනවා
    $update_query = "UPDATE products SET stock = '$new_stock' WHERE id = '$p_id'";
    if (mysqli_query($conn, $update_query)) {
        // සාමාන්‍ය Alert එක වෙනුවට අපි URL එකට පොඩි කෑල්ලක් යවනවා (?updated=true කියලා)
        header("Location: admin_stock.php?updated=true");
        exit();
    } else {
        echo "<script>alert('Error updating stock!');</script>";
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-5 mb-5" style="min-height: 60vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">📦 Inventory & Stock Management</h2>
        <a href="admin_pos.php" class="btn btn-outline-dark shadow-sm">Go to POS System</a>
    </div>
    
    <div class="card shadow-sm p-4 bg-white border-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="10%">Product ID</th>
                        <th width="35%">Product Name</th>
                        <th width="15%">Price</th>
                        <th width="15%" class="text-center">Current Stock</th>
                        <th width="25%" class="text-center">Update Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM products ORDER BY id DESC";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            
                            $stock_class = "text-success fw-bold";
                            $alert_msg = "";
                            
                            if ($row['stock'] <= 5) {
                                $stock_class = "text-danger fw-bold";
                                $alert_msg = "<br><span class='badge bg-danger mt-1'>Low Stock!</span>";
                            }
                            if ($row['stock'] == 0) {
                                $stock_class = "text-muted fw-bold";
                                $alert_msg = "<br><span class='badge bg-secondary mt-1'>Out of Stock</span>";
                            }
                            ?>
                            <tr>
                                <td><span class="badge bg-light text-dark border">#<?php echo $row['id']; ?></span></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td>Rs. <?php echo number_format($row['price'], 2); ?></td>
                                <td class="text-center fs-5 <?php echo $stock_class; ?>">
                                    <?php echo $row['stock']; ?>
                                    <?php echo $alert_msg; ?>
                                </td>
                                <td>
                                    <form action="admin_stock.php" method="POST" class="d-flex justify-content-center">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <input type="number" name="new_stock" class="form-control form-control-sm me-2 text-center" 
                                               value="<?php echo $row['stock']; ?>" min="0" required style="width: 100px;">
                                        <button type="submit" name="update_stock_btn" class="btn btn-sm btn-primary px-3 shadow-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4'>No products found in the database.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // URL එකේ '?updated=true' කියලා තියෙනවද බලනවා
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('updated')) {
        Swal.fire({
            title: "Success!",
            text: "Stock has been updated successfully.",
            icon: "success",
            timer: 2000, // තත්පර 2කින් ඔටෝ මැකිලා යනවා
            showConfirmButton: false
        }).then(() => {
            // Alert එක පෙන්නුවට පස්සේ URL එක ආයේ පරණ විදිහටම (clean) කරනවා
            window.history.replaceState(null, null, window.location.pathname);
        });
    }
</script>

<?php include('includes/footer.php'); ?>