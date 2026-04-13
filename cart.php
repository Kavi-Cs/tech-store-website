<?php 
session_start();
require('includes/db_connect.php'); 

// --- 1. Cart එකට Add කරන කොටස (Add to Cart logic) ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    // product_details එකෙන් එන quantity එක ගන්නවා, නැත්තම් 1ක් විදියට ගන්නවා
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Session cart එකක් නැත්තම් අලුතින් හදනවා
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // භාණ්ඩය කලින් තියෙනවා නම් Quantity එක වැඩි කරනවා
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Refresh කළාම ආයෙත් add වෙන එක (Form Resubmission) නවත්තන්න redirect කරනවා
    header("Location: cart.php");
    exit();
}

// --- 2. Cart එකෙන් අයින් කරන කොටස (Remove Item logic) ---
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]); // ඒ භාණ්ඩය Session එකෙන් මකනවා
    }
    header("Location: cart.php");
    exit();
}

// --- 3. Quantity එක + හෝ - කරද්දී Update වෙන කොටස ---
if (isset($_POST['update_cart'])) {
    if (isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            if ($qty > 0) {
                $_SESSION['cart'][$id] = $qty;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
    }
    header("Location: cart.php");
    exit();
}

// Header එක Include කරන්නේ Redirects වලට පස්සේ. නැත්තම් Error එනවා.
include('includes/header.php'); 
?>

<style>
    body { background-color: #f8fafc; }
    .cart-title { font-size: 2.5rem; font-weight: 800; color: #1f2937; letter-spacing: -1px; }
    .cart-item-card { background: white; border-radius: 16px; border: 1px solid #e5e7eb; transition: all 0.3s ease; }
    .cart-item-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.04); border-color: #cbd5e1; }
    .cart-img { width: 90px; height: 90px; object-fit: contain; mix-blend-mode: multiply; }
    .qty-input { width: 50px; text-align: center; border: none; font-weight: bold; background: transparent; outline: none; }
    .qty-btn { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 8px; font-weight: bold; color: #475569; transition: 0.2s; }
    .qty-btn:hover { background: #e2e8f0; color: #0f172a; }
    .remove-btn { color: #ef4444; background: rgba(239, 68, 68, 0.1); border: none; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; font-weight: 600; transition: 0.3s; }
    .remove-btn:hover { background: #ef4444; color: white; }
    .summary-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #e5e7eb; position: sticky; top: 20px; }
    .checkout-btn { padding: 14px; font-size: 1.1rem; border-radius: 12px; font-weight: 700; transition: all 0.3s ease; }
    .checkout-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(2dc255, 0.3); }
</style>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <h2 class="cart-title mb-4">Your Shopping Cart</h2>

    <?php
    // Cart එකේ භාණ්ඩ තියෙනවද කියලා බලනවා
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    ?>
        <form action="cart.php" method="POST">
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <?php
                    $total_amount = 0;

                    // Session එකේ තියෙන භාණ්ඩ එකින් එක අරගෙන Database එකෙන් විස්තර හොයනවා
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        // SQL Injection වලින් ආරක්ෂා වෙන්න string escape කරනවා
                        $safe_product_id = mysqli_real_escape_string($conn, $product_id);
                        $sql = "SELECT * FROM products WHERE id = '$safe_product_id'";
                        $result = mysqli_query($conn, $sql);
                        
                        if ($row = mysqli_fetch_assoc($result)) {
                            $subtotal = $row['price'] * $quantity;
                            $total_amount += $subtotal;
                    ?>
                            <div class="cart-item-card p-3 mb-3 d-flex flex-column flex-md-row align-items-center gap-3">
                                
                                <div class="bg-light rounded-3 p-2 d-flex justify-content-center align-items-center" style="width: 120px; height: 120px;">
                                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="cart-img" alt="Product" onerror="this.onerror=null;this.src='https://via.placeholder.com/100?text=No+Img';">
                                </div>
                                
                                <div class="flex-grow-1 text-center text-md-start">
                                    <a href="product_details.php?id=<?php echo $product_id; ?>" class="text-decoration-none text-dark">
                                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    </a>
                                    <span class="badge bg-light text-secondary border mb-2"><?php echo htmlspecialchars($row['category']); ?></span>
                                    <h6 class="text-primary fw-bold mb-0">Rs. <?php echo number_format($row['price'], 2); ?></h6>
                                </div>
                                
                                <div class="d-flex align-items-center bg-light rounded-3 p-1 border">
                                    <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); document.getElementById('update-cart-btn').click();">-</button>
                                    <input type="number" name="qty[<?php echo $product_id; ?>]" class="qty-input" value="<?php echo $quantity; ?>" min="1" max="10" onchange="document.getElementById('update-cart-btn').click();">
                                    <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); document.getElementById('update-cart-btn').click();">+</button>
                                </div>
                                
                                <div class="text-end ms-md-4 text-center text-md-end" style="min-width: 120px;">
                                    <h5 class="fw-bold text-dark mb-2">Rs. <?php echo number_format($subtotal, 2); ?></h5>
                                    <a href="cart.php?remove=<?php echo $product_id; ?>" class="remove-btn text-decoration-none d-inline-block">
                                        <i class="bi bi-trash3-fill me-1"></i> Remove
                                    </a>
                                </div>

                            </div>
                    <?php
                        }
                    }
                    ?>
                    
                    <button type="submit" name="update_cart" id="update-cart-btn" class="d-none">Update</button>
                    
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-outline-dark rounded-pill px-4 fw-bold"><i class="bi bi-arrow-left me-2"></i> Continue Shopping</a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="summary-card">
                        <h4 class="fw-bold mb-4 border-bottom pb-3 text-dark">Order Summary</h4>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Subtotal</span>
                            <span class="fw-bold text-dark">Rs. <?php echo number_format($total_amount, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Shipping</span>
                            <span class="fw-bold text-success">Calculated at checkout</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4 pt-3 border-top">
                            <span class="fs-5 fw-bold text-dark">Total</span>
                            <span class="fs-4 fw-bolder text-danger">Rs. <?php echo number_format($total_amount, 2); ?></span>
                        </div>
                        
                        <a href="checkout.php" class="btn btn-success w-100 checkout-btn d-flex justify-content-center align-items-center gap-2">
                            Proceed to Checkout <i class="bi bi-shield-lock-fill"></i>
                        </a>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-2"><i class="bi bi-credit-card-2-front text-secondary me-1"></i> Secure Payments</p>
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" width="35" class="mx-1" alt="Visa">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" width="35" class="mx-1" alt="Mastercard">
                            <img src="https://cdn-icons-png.flaticon.com/512/1071/1071060.png" width="35" class="mx-1" alt="COD">
                        </div>
                    </div>
                </div>

            </div>
        </form>

    <?php
    } else {
        // Cart එක හිස් නම් පෙන්වන පණිවිඩය (Premium Empty State)
    ?>
        <div class="text-center py-5 my-5 bg-white rounded-4 shadow-sm border" style="border-color: #e5e7eb !important;">
            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart" class="mb-4" style="width: 150px; opacity: 0.8;">
            <h2 class="fw-bold text-dark mb-3">Your cart is empty</h2>
            <p class="text-muted mb-4 fs-5">Looks like you haven't added anything to your cart yet.</p>
            <a href="index.php" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">Start Shopping</a>
        </div>
    <?php
    }
    ?>

</div>

<?php include('includes/footer.php'); ?>