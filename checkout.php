<?php 
include('includes/header.php'); 

// Cart එක හිස් නම් ආපසු මුල් පිටුවට යවනවා
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}
?>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <h2 class="mb-4">Checkout</h2>
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Billing Details</h4>
                <form action="place_order.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shipping Address</label>
                        <textarea name="address" class="form-control" rows="3" required placeholder="123 Main St, City"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required placeholder="07X XXX XXXX">
                    </div>

                    <div class="mb-4 mt-4 border-top pt-4">
                        <label class="form-label fw-bold">Select Payment Method</label>
                        <select name="payment_method" id="paymentMethod" class="form-select" onchange="togglePaymentOptions()" required>
                            <option value="" disabled selected>Choose a payment option...</option>
                            <option value="Card">Credit / Debit Card</option>
                            <option value="Cash">Cash (In-store)</option>
                            <option value="Koko">Koko (Buy Now Pay Later)</option>
                            <option value="COD">Cash on Delivery (+ Rs. 500 Delivery Charge)</option>
                        </select>
                    </div>

                    <div id="cardOptions" class="mb-4" style="display: none; background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ced4da;">
                        <h6 class="fw-bold text-dark mb-3">💳 Enter Card Details</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Name on Card</label>
                            <input type="text" name="card_name" class="form-control" placeholder="E.g. JOHN DOE">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Card Number</label>
                            <input type="text" name="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" maxlength="19">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">Expiry Date</label>
                                <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">CVV</label>
                                <input type="password" name="card_cvv" class="form-control" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        <div class="mt-3 text-muted text-center" style="font-size: 12px;">
                            <small>🔒 Your payment details are secured with 256-bit encryption.</small>
                        </div>
                    </div>

                    <div id="kokoOptions" class="mb-4" style="display: none; background-color: #f0f8ff; padding: 15px; border-radius: 8px; border: 1px solid #0d6efd;">
                        <label class="form-label fw-bold text-primary">Select Koko Installment Plan</label>
                        <select name="koko_installments" class="form-select border-primary">
                            <option value="3">3 Installments</option>
                            <option value="6">6 Installments</option>
                            <option value="12">12 Installments</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-2 fw-bold shadow-sm">Place Order</button>
                </form>
            </div>
        </div>

        <div class="col-md-5 mt-4 mt-md-0">
            <div class="alert alert-info shadow-sm">
                <h5>Order Summary</h5>
                <p>You have <strong><?php echo count($_SESSION['cart']); ?></strong> different item(s) in your cart ready to be ordered.</p>
                <p class="mb-0 text-muted small">Once you click 'Place Order', your cart items will be saved to our database securely.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePaymentOptions() {
    var method = document.getElementById('paymentMethod').value;
    var kokoDiv = document.getElementById('kokoOptions');
    var cardDiv = document.getElementById('cardOptions');
    
    // මුලින්ම කොටස් දෙකම හංගනවා
    kokoDiv.style.display = 'none';
    cardDiv.style.display = 'none';
    
    // තෝරන එක අනුව අදාළ කොටස විතරක් පෙන්වනවා
    if (method === 'Koko') {
        kokoDiv.style.display = 'block';
    } else if (method === 'Card') {
        cardDiv.style.display = 'block';
    }
}

// URL එකේ error එකක් ආවොත් ඒක ලස්සනට පෙන්නන්න
const urlParams = new URLSearchParams(window.location.search);
const errorMsg = urlParams.get('error');

if (errorMsg) {
    let errorText = "Something went wrong. Please try again.";
    if(errorMsg === "payment_failed") {
        errorText = "Payment failed! Please check your card details.";
    } else if(errorMsg === "empty_cart") {
        errorText = "Your cart is empty!";
    }

    Swal.fire({
        title: "Oops!",
        text: errorText,
        icon: "error",
        confirmButtonColor: '#d33'
    }).then(() => {
        window.history.replaceState(null, null, window.location.pathname);
    });
}
</script>

<?php include('includes/footer.php'); ?>