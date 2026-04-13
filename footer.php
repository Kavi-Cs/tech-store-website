</main> <footer class="bg-white pt-5 pb-4 mt-5 border-top">
    <div class="container">
        <div class="row gy-4">
            
            <div class="col-lg-5 pe-lg-5">
                <h3 class="fw-bold mb-4" style="color: #0056b3;"><i class="bi bi-cpu me-2"></i>TECH STORE</h3>
                
                <h2 class="fw-bold mb-3 text-dark" style="font-size: 1.8rem; letter-spacing: -0.5px;">Sign up for new stories<br>and personal offers</h2>
                
                <form action="#" method="POST" class="mb-4 mt-4">
                    <div class="input-group p-1" style="border: 1px solid #e5e7eb; border-radius: 12px; background: #fafafa;">
                        <input type="email" class="form-control border-0 shadow-none bg-transparent" placeholder="Email address" required style="font-size: 0.95rem;">
                        <button class="btn btn-light border-0 d-flex align-items-center justify-content-center" type="submit" style="background: #e5e7eb; border-radius: 50%; width: 40px; height: 40px;">
                            <i class="bi bi-chevron-right text-dark" style="font-size: 0.9rem;"></i>
                        </button>
                    </div>
                </form>
                
                <div class="d-flex gap-4 mt-4">
                    <a href="#" class="text-dark fs-5 social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-dark fs-5 social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-dark fs-5 social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-dark fs-5 social-icon"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="text-dark fs-5 social-icon"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 mt-5 mt-lg-0">
                <h6 class="fw-bold mb-4 text-dark fs-5">Shop</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3"><a href="index.php" class="text-muted text-decoration-none">New Arrivals</a></li>
                    <li class="mb-3"><a href="index.php?category=Mobile Phones" class="text-muted text-decoration-none">Smartphones</a></li>
                    <li class="mb-3"><a href="index.php?category=Laptops" class="text-muted text-decoration-none">Laptops & MacBooks</a></li>
                    <li class="mb-3"><a href="index.php?category=Accessories" class="text-muted text-decoration-none">Audio & Accessories</a></li>
                    <li class="mb-3"><a href="index.php?category=Smartwatches" class="text-muted text-decoration-none">Smartwatches</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-4 mt-5 mt-lg-0">
                <h6 class="fw-bold mb-4 text-dark fs-5">About</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Our Story</a></li>
                    <li class="mb-3"><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                    <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Contact Us</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-4 mt-5 mt-lg-0">
                <h6 class="fw-bold mb-4 text-dark fs-5">Policies</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Terms & Conditions</a></li>
                    <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Return & Refund Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="row mt-5 pt-4">
            <div class="col-12">
                <p class="text-muted mb-0" style="font-size: 0.95rem;">&copy; <?php echo date("Y"); ?> All rights reserved by Tech Store.</p>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-links a {
        font-size: 0.95rem;
        transition: color 0.3s ease, padding-left 0.3s ease;
    }
    .footer-links a:hover {
        color: var(--primary-color) !important;
        padding-left: 6px; /* මවුස් එක ගෙනිච්චම ලින්ක් එක පොඩ්ඩක් ඉස්සරහට එනවා */
    }
    .social-icon {
        transition: transform 0.3s ease, color 0.3s ease;
    }
    .social-icon:hover {
        color: var(--primary-color) !important;
        transform: translateY(-4px); /* මවුස් එක ගෙනිච්චම අයිකන් එක උඩට ඉස්සෙනවා */
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>