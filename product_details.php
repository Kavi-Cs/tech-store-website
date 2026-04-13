<?php
session_start();
require('includes/db_connect.php');
include('includes/header.php');

// URL එකෙන් Product ID එක ගන්නවා
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT * FROM products WHERE id = '$product_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
    $category = $product['category'];
} else {
    echo "<div class='container mt-5 text-center py-5'>
            <h2 class='fw-bold text-muted'>Product Not Found</h2>
            <a href='index.php' class='btn btn-primary mt-3'>Go Back to Shop</a>
          </div>";
    include('includes/footer.php');
    exit;
}
?>

<style>
    body { background-color: #f0f2f5; }
    .product-detail-bg { background-color: #f0f2f5; }
    .badge-sale { background-color: #8b5cf6; font-size: 0.9rem; padding: 6px 16px; font-weight: 600; }
    .product-title { font-size: 3rem; color: #1f2937; letter-spacing: -1px; line-height: 1.2; font-weight: 800; }
    .product-price { font-size: 1.8rem; color: #dc2626; font-weight: 700; }
    .spec-list li { font-size: 1.1rem; color: #374151; margin-bottom: 10px; display: flex; }
    .spec-list li .bullet { margin-right: 12px; color: #6b7280; font-size: 1.2rem; }
    .warranty-text { font-size: 1.3rem !important; font-weight: 800 !important; color: #111827; margin-top: 20px !important; }
    
    /* Social Share Buttons */
    .share-btn { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; transition: transform 0.3s ease; }
    .share-btn:hover { transform: translateY(-3px); color: white; }
    .bg-whatsapp { background-color: #25D366; }
    .bg-facebook { background-color: #1877F2; }
    .bg-twitter { background-color: #000000; }
    
    /* Accordion Premium Style */
    .accordion-item { border: none; border-radius: 12px !important; overflow: hidden; margin-bottom: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    .accordion-button { font-weight: 600; color: #1f2937; background-color: #ffffff !important; padding: 18px 20px; box-shadow: none !important; }
    .accordion-button:not(.collapsed) { color: #2563eb; background-color: #f8fafc !important; }
    
    /* Specs Table */
    .table-specs th { background-color: #f8fafc; color: #6b7280; font-weight: 500; width: 35%; padding: 15px; border-bottom: 1px solid #e5e7eb; }
    .table-specs td { background-color: #ffffff; color: #111827; font-weight: 600; padding: 15px; border-bottom: 1px solid #e5e7eb; }
    
    /* Reviews */
    .review-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f3f4f6; }
</style>

<div class="product-detail-bg py-5">
    <div class="container pb-5">
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php?category=<?php echo urlencode($category); ?>" class="text-decoration-none text-muted"><?php echo htmlspecialchars($category); ?></a></li>
                <li class="breadcrumb-item active fw-bold" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>

        <div class="row g-5 align-items-center bg-white rounded-4 shadow-sm p-4 mb-5 border" style="border-color: #e5e7eb !important;">
            
            <div class="col-lg-5 text-center">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid" style="max-height: 500px; object-fit: contain; mix-blend-mode: multiply;" onerror="this.onerror=null;this.src='https://via.placeholder.com/500?text=No+Image';">
            </div>

            <div class="col-lg-7 ps-lg-5">
                <span class="badge rounded-pill badge-sale mb-3 text-white">Sale!</span>
                
                <h1 class="product-title mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="text-warning me-2 fs-5">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <span class="text-muted">(4.8/5 - 24 Reviews)</span>
                </div>

                <h3 class="product-price mb-4 pb-3 border-bottom">Rs. <?php echo number_format($product['price'], 2); ?></h3>

                <ul class="list-unstyled spec-list mb-4">
                    <?php
                    $description = trim($product['description']);
                    if (!empty($description)) {
                        $lines = explode("\n", $description);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (empty($line)) continue;
                            if (stripos($line, 'warranty') !== false) {
                                echo '<li class="warranty-text"><span class="bullet text-success">✓</span> <span class="text-success">' . htmlspecialchars($line) . '</span></li>';
                            } else {
                                echo '<li><span class="bullet">•</span> <span>' . htmlspecialchars($line) . '</span></li>';
                            }
                        }
                    } else {
                        echo '<li><span class="bullet">•</span> <span>Premium quality guaranteed.</span></li>';
                        echo '<li class="warranty-text"><span class="bullet text-success">✓</span> <span class="text-success">01-Year Company Warranty included.</span></li>';
                    }
                    ?>
                </ul>

                <form action="cart.php" method="POST" class="mt-4 pt-3 border-top d-flex align-items-center gap-3">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    
                    <div class="input-group" style="width: 130px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <button type="button" class="btn btn-light border-0 px-3 fs-5" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
                        <input type="number" name="quantity" class="form-control text-center border-0 fw-bold fs-5" value="1" min="1" max="10">
                        <button type="button" class="btn btn-light border-0 px-3 fs-5" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                    </div>
                    
                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg px-4 fw-bold rounded-pill shadow-sm" style="flex: 1;">
                        <i class="bi bi-cart-plus me-2"></i> Add to Cart
                    </button>
                </form>
                
                <div class="d-flex align-items-center gap-3 mt-4 pt-3">
                    <span class="text-muted fw-bold small">Share this product:</span>
                    <a href="https://api.whatsapp.com/send?text=Check out this <?php echo urlencode($product['name']); ?>!" target="_blank" class="share-btn bg-whatsapp"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=#" target="_blank" class="share-btn bg-facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="share-btn bg-twitter"><i class="bi bi-twitter-x"></i></a>
                </div>

            </div>
        </div>

        <div class="row g-5">
            
            <div class="col-lg-7">
                
                <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-list-columns-reverse text-primary me-2"></i>Technical Specifications</h4>
                <div class="table-responsive bg-white rounded-4 shadow-sm border mb-5" style="border-color: #e5e7eb !important;">
                    <table class="table table-borderless table-specs mb-0">
                        <tbody>
                            <tr><th>Category</th><td><?php echo htmlspecialchars($category); ?></td></tr>
                            <tr><th>Brand</th><td>Genuine Apple / Android (Dynamic)</td></tr>
                            <tr><th>Condition</th><td>100% Brand New - Factory Sealed</td></tr>
                            <tr><th>Stock Status</th><td><span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>In Stock</span></td></tr>
                            <tr><th>Delivery</th><td>Island-wide Delivery within 24-48 Hours</td></tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-question-circle-fill text-warning me-2"></i>Frequently Asked Questions</h4>
                <div class="accordion mb-5" id="productFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">What is the return policy?</button></h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#productFAQ">
                            <div class="accordion-body text-muted">We offer a 7-day checking warranty. If the product has a factory defect, we will replace it immediately.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">What payment methods do you accept?</button></h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#productFAQ">
                            <div class="accordion-body text-muted">We accept Cash on Delivery (COD), Visa/Mastercard payments, Bank Transfers, and Koko (Buy Now Pay Later).</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">Is the warranty claimed locally?</button></h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#productFAQ">
                            <div class="accordion-body text-muted">Yes, all warranties can be claimed at our main service center or authorized partner outlets in Sri Lanka.</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-5">
                <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-chat-quote-fill text-success me-2"></i>Customer Reviews</h4>
                
                <div class="review-card mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://ui-avatars.com/api/?name=Kasun+Perera&background=0D8ABC&color=fff" class="rounded-circle me-3" width="45">
                        <div>
                            <h6 class="mb-0 fw-bold">Kasun Perera</h6>
                            <div class="text-warning small"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 mt-2">"Super fast delivery! Got the product within 24 hours to Kandy. 100% genuine and the packing was perfect. Highly recommended."</p>
                </div>

                <div class="review-card mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <img src="https://ui-avatars.com/api/?name=Amandi+S&background=f43f5e&color=fff" class="rounded-circle me-3" width="45">
                        <div>
                            <h6 class="mb-0 fw-bold">Amandi S.</h6>
                            <div class="text-warning small"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 mt-2">"Great customer service. The staff explained everything clearly. The item works perfectly fine without any issues."</p>
                </div>
            </div>

        </div>

        <div class="mt-5 pt-5 border-top border-2">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark">You Might Also Like</h3>
                <a href="index.php?category=<?php echo urlencode($category); ?>" class="btn btn-outline-primary rounded-pill btn-sm fw-bold px-3">View All</a>
            </div>
            
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php
                // අදාල Category එකේම තියෙන වෙනත් භාණ්ඩ 4ක් පෙන්වීම
                $related_query = "SELECT * FROM products WHERE category = '$category' AND id != '$product_id' ORDER BY RAND() LIMIT 4";
                $related_result = mysqli_query($conn, $related_query);

                if (mysqli_num_rows($related_result) > 0) {
                    while ($row = mysqli_fetch_assoc($related_result)) {
                        ?>
                        <div class="col">
                            <div class="card h-100 text-center pb-3 border-0 shadow-sm" style="border-radius: 12px;">
                                <a href="product_details.php?id=<?php echo $row['id']; ?>">
                                    <img src="<?php echo $row['image_url']; ?>" class="card-img-top mx-auto mt-3" style="height: 150px; object-fit: contain;" alt="Product">
                                </a>
                                <div class="card-body pt-2">
                                    <a href="product_details.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                        <h6 class="card-title fw-bold mb-1 text-truncate px-2" title="<?php echo htmlspecialchars($row['name']); ?>"><?php echo htmlspecialchars($row['name']); ?></h6>
                                    </a>
                                    <h6 class="text-danger fw-bold mt-2">Rs. <?php echo number_format($row['price'], 2); ?></h6>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-muted'>No related products found.</p>";
                }
                ?>
            </div>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>