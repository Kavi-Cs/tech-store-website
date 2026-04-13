<?php
session_start();
require('includes/db_connect.php');

$success_message = "";

// Form එක Submit කරාම වෙන දේ (දැනට මැසේජ් එකක් ගියා කියලා පෙන්වන්න විතරක් හදලා තියෙන්නේ)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // මෙතනදී ඔයාට ඕන නම් මේ විස්තර Database එකට සේව් කරන්න පුළුවන් (දැනට Success Message එකක් විතරක් පෙන්වමු)
    $success_message = "Thank you, <strong>$name</strong>! Your message has been sent successfully. We will get back to you soon.";
}

include('includes/header.php');
?>

<style>
    body { background-color: #f8fafc; }
    .contact-hero { background: linear-gradient(135deg, #0f172a, #1e293b); color: white; padding: 60px 0; border-radius: 0 0 30px 30px; margin-top: -24px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .info-card { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #e5e7eb; height: 100%; transition: 0.3s; }
    .info-card:hover { box-shadow: 0 15px 35px rgba(0,0,0,0.06); transform: translateY(-5px); }
    .icon-box { width: 50px; height: 50px; border-radius: 12px; background: rgba(13, 110, 253, 0.1); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 15px; }
    .form-control { border-radius: 10px; padding: 12px 15px; border: 1px solid #cbd5e1; background-color: #f8fafc; transition: 0.3s; }
    .form-control:focus { background-color: white; border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1); }
    .send-btn { border-radius: 10px; padding: 12px; font-weight: bold; font-size: 1.1rem; transition: 0.3s; }
    .send-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3); }
    .map-container { border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
</style>

<div class="contact-hero text-center mb-5">
    <div class="container">
        <h1 class="display-4 fw-bolder mb-3">Get in Touch</h1>
        <p class="lead" style="color: #cbd5e1;">Have a question about our products? We'd love to hear from you!</p>
    </div>
</div>

<div class="container mb-5">
    
    <?php if(!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-5" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-5">
        
        <div class="col-lg-5">
            <div class="info-card">
                <h3 class="fw-bold text-dark mb-3">About Tech Store</h3>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    Welcome to Tech Store, Sri Lanka's premium destination for top-quality laptops, mobile phones, smartwatches, and accessories. We are committed to providing you with the best gadgets at unbeatable prices with an exclusive warranty and 24/7 customer support.
                </p>
                
                <hr class="text-muted mb-4">
                
                <h4 class="fw-bold text-dark mb-4">Contact Information</h4>
                
                <div class="d-flex align-items-start mb-4">
                    <div class="icon-box me-3 flex-shrink-0">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Our Store Location</h6>
                        <p class="text-muted mb-0">No 123, Galle Road,<br>Colombo 03, Sri Lanka.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="icon-box me-3 flex-shrink-0 bg-success-subtle text-success">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Call Us</h6>
                        <p class="text-muted mb-0">+94 11 234 5678<br>+94 77 123 4567</p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="icon-box me-3 flex-shrink-0 bg-danger-subtle text-danger">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email Us</h6>
                        <p class="text-muted mb-0">support@techstore.lk<br>sales@techstore.lk</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="info-card">
                <h3 class="fw-bold text-dark mb-4">Send Us a Message</h3>
                
                <form action="contact.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Your Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Product inquiry, Support, etc." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">Message</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="send_message" class="btn btn-primary w-100 send-btn">
                                <i class="bi bi-send-fill me-2"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div class="row mt-5 pt-3">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-4 text-center">Find Us on Google Maps</h3>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126743.58585976566!2d79.77380315891783!3d6.921838644558223!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo!5e0!3m2!1sen!2slk!4v1714035600000!5m2!1sen!2slk" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>