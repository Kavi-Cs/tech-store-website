<?php
session_start();
require('includes/db_connect.php');

// Admin කෙනෙක් නෙවෙයි නම් මුල් පිටුවට හරවා යැවීම
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// URL එකෙන් Product ID එක ලබා ගැනීම
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// අලුත් විස්තර Save කිරීම (Update කිරීම)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    $update_sql = "UPDATE products SET name='$name', price='$price', description='$description', image_url='$image_url' WHERE id='$id'";
    
    if (mysqli_query($conn, $update_sql)) {
        // සාර්ථකව Update වුණාම ආපහු Products පිටුවට යවනවා
        echo "<script>
                alert('Product updated successfully!');
                window.location.href='admin_products.php';
              </script>";
        exit();
    } else {
        $error = "Failed to update product: " . mysqli_error($conn);
    }
}

// පරණ විස්තර Database එකෙන් ලබා ගැනීම
$sql = "SELECT * FROM products WHERE id='$id'";
$result = mysqli_query($conn, $sql);

// එහෙම Product එකක් නැත්නම් ආපහු හරවා යැවීම
if(mysqli_num_rows($result) == 0) {
    header("Location: admin_products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);
include('includes/header.php');
?>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Edit Product (#<?php echo $product['id']; ?>)</h4>
                    <a href="admin_products.php" class="btn btn-secondary btn-sm">Cancel</a>
                </div>

                <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                <form action="admin_edit_product.php?id=<?php echo $product['id']; ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="text" name="image_url" class="form-control" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    <button type="submit" name="update_product" class="btn btn-success w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>