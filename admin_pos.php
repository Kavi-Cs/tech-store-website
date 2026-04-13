<?php 
session_start();
include('includes/header.php'); // ඔයාගේ සාමාන්‍ය Header එක
require('includes/db_connect.php');
?>

<div class="container-fluid mt-4 mb-5">
    <h2 class="mb-4 text-primary"><i class="bi bi-shop"></i> POS System (In-Store Billing)</h2>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm p-3 mb-4" style="height: 70vh; overflow-y: auto;">
                <h5 class="border-bottom pb-2">Select Products</h5>
                <div class="row">
                    <?php
                    // Database එකෙන් බඩු ටික අදිනවා
                    $query = "SELECT * FROM products";
                    $result = mysqli_query($conn, $query);
                    while($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-primary text-center p-2" 
                                 style="cursor: pointer;" 
                                 onclick="addToCart(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>)">
                                <h6 class="mt-2 text-dark"><?php echo $row['name']; ?></h6>
                                <p class="text-danger fw-bold mb-0">Rs. <?php echo number_format($row['price'], 2); ?></p>
                                <button class="btn btn-sm btn-outline-primary mt-2">Add to Bill</button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm p-4 bg-light">
                <h4 class="mb-3 border-bottom pb-2">Current Bill</h4>
                
                <form action="pos_place_order.php" method="POST" id="posForm">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small mb-1">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control form-control-sm" value="Walk-in Customer">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">Phone Number (Optional)</label>
                            <input type="text" name="phone" class="form-control form-control-sm" placeholder="07X XXX XXXX">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small mb-1">Payment Method</label>
                        <select name="payment_method" class="form-select form-select-sm" required>
                            <option value="Cash (In-store)">Cash</option>
                            <option value="Card (In-store)">Credit / Debit Card</option>
                        </select>
                    </div>

                    <table class="table table-sm table-bordered mt-3 bg-white">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Item</th>
                                <th width="20%">Qty</th>
                                <th>Total</th>
                                <th>X</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">
                            </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
                        <h5 class="mb-0">Grand Total:</h5>
                        <h4 class="mb-0 text-danger fw-bold">Rs. <span id="grandTotal">0.00</span></h4>
                        <input type="hidden" name="total_amount" id="hiddenTotal" value="0">
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 mt-4 fw-bold shadow-sm">🚀 Print Bill & Complete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let cart = {};

function addToCart(id, name, price) {
    if(cart[id]) {
        cart[id].qty += 1;
    } else {
        cart[id] = { name: name, price: price, qty: 1 };
    }
    updateCartUI();
}

function updateQty(id, newQty) {
    if(newQty <= 0) {
        delete cart[id];
    } else {
        cart[id].qty = parseInt(newQty);
    }
    updateCartUI();
}

function updateCartUI() {
    let tbody = document.getElementById('cartTableBody');
    tbody.innerHTML = '';
    let grandTotal = 0;

    for (let id in cart) {
        let item = cart[id];
        let itemTotal = item.price * item.qty;
        grandTotal += itemTotal;

        tbody.innerHTML += `
            <tr>
                <td class="align-middle">${item.name}
                    <input type="hidden" name="product_id[]" value="${id}">
                    <input type="hidden" name="price[]" value="${item.price}">
                </td>
                <td>
                    <input type="number" name="quantity[]" value="${item.qty}" class="form-control form-control-sm text-center" onchange="updateQty(${id}, this.value)">
                </td>
                <td class="align-middle text-end">Rs. ${itemTotal.toFixed(2)}</td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-sm btn-danger py-0 px-2" onclick="updateQty(${id}, 0)">X</button>
                </td>
            </tr>
        `;
    }

    document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
    document.getElementById('hiddenTotal').value = grandTotal;
}
</script>

<?php include('includes/footer.php'); ?>