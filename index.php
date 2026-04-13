<?php
session_start();
require('includes/db_connect.php');
include('includes/header.php');

// Search සහ Category අල්ලා ගැනීම
$search_keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : "";

// Database එකෙන් බඩු ගන්න Query එක හැදීම
$sql = "SELECT * FROM products WHERE 1=1"; // 1=1 දාන්නේ ඊළඟට එන AND කෑලි ලේසියෙන් එකතු කරන්න

if (!empty($search_keyword)) {
    $sql .= " AND (name LIKE '%$search_keyword%' OR description LIKE '%$search_keyword%')";
}
if (!empty($category_filter)) {
    $sql .= " AND category = '$category_filter'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<ul class="bg-bubbles">
    <li><i class="bi bi-apple"></i></li>
    <li><i class="bi bi-laptop"></i></li>
    <li><i class="bi bi-phone"></i></li>
    <li><i class="bi bi-smartwatch"></i></li>
    <li><i class="bi bi-headphones"></i></li>
    <li><i class="bi bi-tablet-landscape"></i></li>
    <li><i class="bi bi-controller"></i></li>
    <li><i class="bi bi-mouse3"></i></li>
    <li><i class="bi bi-pc-display"></i></li>
    <li><i class="bi bi-earbuds"></i></li>
</ul>

<div class="container px-3 mt-4">
    <div id="mainSlider" class="carousel slide carousel-fade mb-5" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000">
        
        <div class="carousel-inner" style="border-radius: 15px;">
            <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1920&auto=format&fit=crop');">
                <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=1920&auto=format&fit=crop');">
                <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=1920&auto=format&fit=crop');">
                <div class="carousel-overlay"></div>
            </div>
        </div>

        <div class="carousel-content text-center px-4">
            <h1 class="display-4 fw-bold mb-3 text-white" style="text-shadow: 2px 2px 10px rgba(0,0,0,0.8);">Welcome to Tech Store</h1>
            <p class="lead mb-4 text-light" style="text-shadow: 1px 1px 5px rgba(0,0,0,0.8);">Find the best Laptops, Mobile Phones, and Accessories at unbeatable prices.</p>
            
            <form action="index.php" method="GET" class="d-flex justify-content-center mx-auto" style="max-width: 600px;">
                <input type="text" name="search" class="form-control form-control-lg me-2 border-0 shadow-lg" placeholder="Search for products..." value="<?php echo htmlspecialchars($search_keyword); ?>">
                <button type="submit" class="btn btn-primary btn-lg px-4 shadow-lg text-white fw-bold">Search</button>
                <?php if(!empty($search_keyword) || !empty($category_filter)): ?>
                    <a href="index.php" class="btn btn-light btn-lg ms-2 text-dark shadow-lg fw-bold">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<div class="container mb-5" style="min-height: 40vh;">
    
    <div class="container mb-5" style="margin-top: -30px; position: relative; z-index: 10;">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 justify-content-center">
            
            <div class="col">
                <a href="index.php" class="text-decoration-none">
                    <div class="category-img-card shadow-sm <?php echo empty($category_filter) ? 'border-primary border-2' : ''; ?>" style="background: #fff; border-radius: 12px; padding: 15px; transition: 0.3s; text-align: center;">
                        <i class="bi bi-grid-fill" style="font-size: 2.5rem; color: #0d6efd; display: block; margin-bottom: 10px;"></i>
                        <h6 class="fw-bold mb-0 text-center text-dark">All Products</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="index.php?category=Laptops" class="text-decoration-none">
                    <div class="category-img-card shadow-sm <?php echo ($category_filter == 'Laptops') ? 'border-primary border-2' : ''; ?>" style="background: #fff; border-radius: 12px; padding: 15px; transition: 0.3s; text-align: center;">
                        <i class="bi bi-laptop" style="font-size: 2.5rem; color: #495057; display: block; margin-bottom: 10px;"></i>
                        <h6 class="fw-bold mb-0 text-center text-dark">Laptops</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="index.php?category=Mobile Phones" class="text-decoration-none">
                    <div class="category-img-card shadow-sm <?php echo ($category_filter == 'Mobile Phones') ? 'border-primary border-2' : ''; ?>" style="background: #fff; border-radius: 12px; padding: 15px; transition: 0.3s; text-align: center;">
                        <i class="bi bi-phone" style="font-size: 2.5rem; color: #495057; display: block; margin-bottom: 10px;"></i>
                        <h6 class="fw-bold mb-0 text-center text-dark">Mobile Phones</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="index.php?category=Accessories" class="text-decoration-none">
                    <div class="category-img-card shadow-sm <?php echo ($category_filter == 'Accessories') ? 'border-primary border-2' : ''; ?>" style="background: #fff; border-radius: 12px; padding: 15px; transition: 0.3s; text-align: center;">
                        <i class="bi bi-headphones" style="font-size: 2.5rem; color: #495057; display: block; margin-bottom: 10px;"></i>
                        <h6 class="fw-bold mb-0 text-center text-dark">Accessories</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="index.php?category=Smartwatches" class="text-decoration-none">
                    <div class="category-img-card shadow-sm <?php echo ($category_filter == 'Smartwatches') ? 'border-primary border-2' : ''; ?>" style="background: #fff; border-radius: 12px; padding: 15px; transition: 0.3s; text-align: center;">
                        <i class="bi bi-smartwatch" style="font-size: 2.5rem; color: #495057; display: block; margin-bottom: 10px;"></i>
                        <h6 class="fw-bold mb-0 text-center text-dark">Smartwatches</h6>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <h3 class="text-center mb-4 fw-bold text-secondary mt-5">
        <?php 
        if(!empty($category_filter)) {
            echo htmlspecialchars($category_filter);
        } elseif(!empty($search_keyword)) {
            echo "Search Results for: '" . htmlspecialchars($search_keyword) . "'";
        } else {
            echo "Featured Products";
        }
        ?>
    </h3>
    
    <div class="row row-cols-1 row-cols-md-4 g-4"> 
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col">
                    <div class="card h-100 text-center pb-3">
                        
                        <a href="product_details.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo $row['image_url']; ?>" class="card-img-top mx-auto mt-3" style="height: 180px; object-fit: contain; mix-blend-mode: multiply;" alt="Product" onerror="this.onerror=null;this.src='https://via.placeholder.com/200?text=No+Img';">
                        </a>
                        
                        <div class="card-body pt-3">
                            <?php $cat = isset($row['category']) ? $row['category'] : 'General'; ?>
                            <span class="badge bg-light text-primary border border-primary mb-2"><?php echo $cat; ?></span>
                            
                            <a href="product_details.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                <h6 class="card-title fw-bold mb-1 px-2 text-truncate" title="<?php echo htmlspecialchars($row['name']); ?>"><?php echo htmlspecialchars($row['name']); ?></h6>
                            </a>
                            
                            <h5 class="text-danger fw-bold mt-2 mb-3">Rs. <?php echo number_format($row['price'], 2); ?></h5>
                            
                            <form action="cart.php" method="POST" class="px-3">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary w-100 text-uppercase text-white fw-bold rounded-pill shadow-sm" style="font-size: 0.85rem; padding: 10px 0;">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div class='col-12 text-center py-5'>
                    <h4 class='text-muted'>No products found.</h4>
                  </div>";
        }
        ?>
    </div>
</div>

<div class="live-info-bar mt-5 bg-dark text-white py-3 border-top border-primary border-3">
    <div class="scrolling-text d-inline-flex align-items-center fw-bold">
        
        <div class="marquee-content d-inline-flex">
            <span class="mx-5"><i class="bi bi-geo-alt-fill text-danger me-2 fs-4 align-middle"></i>Our Branches: Colombo | Kandy | Galle | Kurunegala</span>
            <span class="mx-5"><i class="bi bi-truck text-warning me-2 fs-4 align-middle"></i>Islandwide Delivery Available (Within 24-48 Hours!)</span>
            <span class="mx-5"><i class="bi bi-telephone-fill text-success me-2 fs-4 align-middle"></i>Hotline: 011-2345678 | 077-1234567</span>
            <span class="mx-5"><i class="bi bi-headset text-info me-2 fs-4 align-middle"></i>24/7 Customer Support Available</span>
        </div>
        
        <div class="marquee-content d-inline-flex">
            <span class="mx-5"><i class="bi bi-geo-alt-fill text-danger me-2 fs-4 align-middle"></i>Our Branches: Colombo | Kandy | Galle | Kurunegala</span>
            <span class="mx-5"><i class="bi bi-truck text-warning me-2 fs-4 align-middle"></i>Islandwide Delivery Available (Within 24-48 Hours!)</span>
            <span class="mx-5"><i class="bi bi-telephone-fill text-success me-2 fs-4 align-middle"></i>Hotline: 011-2345678 | 077-1234567</span>
            <span class="mx-5"><i class="bi bi-headset text-info me-2 fs-4 align-middle"></i>24/7 Customer Support Available</span>
        </div>
        
    </div>
</div>
<?php include('includes/footer.php'); ?>