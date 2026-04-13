<?php
session_start();
require('includes/db_connect.php');

// Admin කෙනෙක් නෙවෙයි නම් මුල් පිටුවට හරවා යැවීම
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 1. භාණ්ඩයක් මකා දැමීම (Delete Product)
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_sql = "DELETE FROM products WHERE id = '$delete_id'";
    
    if (mysqli_query($conn, $delete_sql)) {
        // මකා දැමුවට පසු URL එකට msg=deleted යවනවා
        header("Location: admin_products.php?msg=deleted");
        exit();
    } else {
        header("Location: admin_products.php?msg=error");
        exit();
    }
}

// 2. අලුත් Product එකක් Form එකෙන් Submit කරාම Database එකට දැමීම
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $sql = "INSERT INTO products (name, price, description, image_url, category) VALUES ('$name', '$price', '$description', '$image_url', '$category')";
    
    if (mysqli_query($conn, $sql)) {
        // සාර්ථකව ඇඩ් වුණාම URL එකට msg=added යවනවා
        header("Location: admin_products.php?msg=added");
        exit();
    } else {
        header("Location: admin_products.php?msg=error");
        exit();
    }
}

include('includes/header.php');
?>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Products</h2>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                <h4 class="mb-3">Add New Product</h4>
                <form action="admin_products.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="Laptops">Laptops</option>
                            <option value="Mobile Phones">Mobile Phones</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Smartwatches">Smartwatches</option>
                            <option value="General">General</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary w-100 fw-bold">Add Product</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm p-4 border-0" style="border-radius: 15px;">
                <h4 class="mb-3">Current Products</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fetch_products = "SELECT * FROM products ORDER BY id DESC";
                            $result = mysqli_query($conn, $fetch_products);
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><span class='badge bg-light text-dark border'>#{$row['id']}</span></td>";
                                echo "<td><img src='{$row['image_url']}' width='50' style='object-fit: contain; height: 50px;' onerror=\"this.onerror=null;this.src='https://via.placeholder.com/50?text=No+Img';\" alt='product'></td>";
                                echo "<td class='fw-bold'>{$row['name']}</td>";
                                
                                $cat = isset($row['category']) ? $row['category'] : 'General';
                                echo "<td><span class='badge bg-secondary'>{$cat}</span></td>";
                                
                                echo "<td class='text-primary fw-bold'>$" . number_format($row['price'], 2) . "</td>";
                                echo "<td>
                                        <a href='admin_edit_product.php?id={$row['id']}' class='btn btn-sm btn-warning me-1'>Edit</a>
                                        <button onclick='confirmDelete({$row['id']})' class='btn btn-sm btn-danger'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. URL එකේ තියෙන msg එක අනුව Alert එක පෙන්වීම
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');

    if (msg) {
        let titleText = "";
        let descText = "";
        let iconType = "success";

        if (msg === "added") {
            titleText = "Added!";
            descText = "Product has been added successfully.";
        } else if (msg === "updated") {
            titleText = "Updated!";
            descText = "Product details updated successfully.";
        } else if (msg === "deleted") {
            titleText = "Deleted!";
            descText = "Product has been removed.";
            iconType = "warning"; // මකා දැමූ නිසා Warning අයිකන් එක
        } else if (msg === "error") {
            titleText = "Error!";
            descText = "Something went wrong. Please try again.";
            iconType = "error";
        }

        if(titleText !== "") {
            Swal.fire({
                title: titleText,
                text: descText,
                icon: iconType,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Alert එකෙන් පස්සේ URL එක පිරිසිදු කිරීම
                window.history.replaceState(null, null, window.location.pathname);
            });
        }
    }

    // 2. Delete බොත්තම එබුවාම එන අලුත් ලස්සන Confirm Box එක
    function confirmDelete(productId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Yes එබුවොත් විතරක් Delete කරන ලින්ක් එකට යනවා
                window.location.href = 'admin_products.php?delete_id=' + productId;
            }
        });
    }
</script>

<?php include('includes/footer.php'); ?>