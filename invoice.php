<?php
session_start();
require('includes/db_connect.php'); 

// URL එකෙන් Order ID එක ඇවිත්ද කියලා බලනවා
if (!isset($_GET['order_id'])) {
    die("<h3 class='text-center mt-5'>Order ID is missing! Please go back and try again.</h3>");
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

// Database එකෙන් අදාළ Order එකේ විස්තර අදිනවා
$order_query = "SELECT * FROM orders WHERE id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    die("<h3 class='text-center mt-5'>Order not found!</h3>");
}

$order_data = mysqli_fetch_assoc($order_result);

// Database එකෙන් ගත්ත ඇත්තම දත්ත විචල්‍ය (Variables) වලට දාගන්නවා
$customer_name = $order_data['customer_name'];
$phone = $order_data['phone'];
$address = $order_data['address'];
$payment_method = $order_data['payment_method'];
$date = date("Y-m-d H:i:s", strtotime($order_data['created_at']));
$grand_total = $order_data['total_amount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* A4 Size Styling */
        body {
            background-color: #f0f0f0;
        }
        .a4-container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: white;
            padding: 20mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        
        /* Print කරනකොට වෙනස් වෙන්න ඕනේ දේවල් */
        @media print {
            body { background-color: white; margin: 0; padding: 0; }
            .a4-container { box-shadow: none; margin: 0; padding: 10mm; width: 100%; }
            .no-print { display: none !important; }
        }
        .seal-box {
            width: 150px; 
            height: 80px; 
            border: 2px dashed #aaa; 
            margin: auto; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            color: #ccc;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-lg fw-bold shadow">🖨️ Print Bill / Save as PDF</button>
    <a href="admin_pos.php" class="btn btn-secondary btn-lg fw-bold shadow ms-2">Back to POS</a>
</div>

<div class="a4-container">
    <div class="row border-bottom pb-4 mb-4">
        <div class="col-6">
            <h2 class="fw-bold text-primary">TECH STORE</h2>
            <p class="mb-0">123, Main Street, Colombo<br>Hotline: 011-2345678</p>
        </div>
        <div class="col-6 text-end">
            <h1 class="text-uppercase text-secondary">Invoice</h1>
            <p class="mb-0 fw-bold">Order ID: #<?php echo $order_id; ?></p>
            <p class="mb-0">Date: <?php echo $date; ?></p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h5 class="fw-bold">Billed To:</h5>
            <p class="mb-0 fs-5"><?php echo htmlspecialchars($customer_name); ?></p>
            <p class="mb-0">Phone: <strong><?php echo htmlspecialchars($phone); ?></strong></p>
            <p class="mb-0 text-muted"><?php echo nl2br(htmlspecialchars($address)); ?></p>
            <p class="mb-0 mt-2">Payment Method: <span class="badge bg-dark fs-6"><?php echo htmlspecialchars($payment_method); ?></span></p>
        </div>
    </div>

    <table class="table table-bordered mb-4">
        <thead class="table-dark">
            <tr>
                <th>Item Description</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Price</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Order Items සහ Products table එකතු කරලා සැබෑ බඩු ටික අදිනවා
            $items_query = "SELECT oi.quantity, oi.price, p.name 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.id 
                            WHERE oi.order_id = '$order_id'";
            $items_result = mysqli_query($conn, $items_query);

            $subtotal = 0;
            while ($item = mysqli_fetch_assoc($items_result)) {
                $item_total = $item['quantity'] * $item['price'];
                $subtotal += $item_total;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                    <td class="text-end">Rs. <?php echo number_format($item['price'], 2); ?></td>
                    <td class="text-end">Rs. <?php echo number_format($item_total, 2); ?></td>
                </tr>
                <?php
            }

            // Delivery Charge එකක් තියෙනවද කියලා බලනවා (Grand total එකයි Subtotal එකයි අතර වෙනස)
            $delivery_charge = $grand_total - $subtotal;
            if ($delivery_charge > 0) {
                ?>
                <tr>
                    <td colspan="3" class="text-end text-muted">Delivery Charge:</td>
                    <td class="text-end text-muted">Rs. <?php echo number_format($delivery_charge, 2); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end fs-5">Grand Total:</th>
                <th class="text-end fs-5 text-danger">Rs. <?php echo number_format($grand_total, 2); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 pt-5 text-center">
        <div class="col-6">
            <hr style="width: 70%; margin: auto; border-top: 1px solid #000;">
            <p class="mt-2 fw-bold text-muted">Customer Signature</p>
        </div>
        <div class="col-6">
            <div class="seal-box">PAID SEAL</div>
            <p class="mt-2 fw-bold text-muted">Authorized Signature / Seal</p>
        </div>
    </div>

    <div class="text-center mt-5 pt-3 border-top">
        <p class="mb-1 fw-bold">Thank you for shopping with us!</p>
        <p class="text-muted small">If you have any questions concerning this invoice, please contact our hotline.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    
    // URL එකේ success=true තියෙනවද බලනවා
    if(urlParams.has('success')) {
        Swal.fire({
            title: "Payment Successful!",
            text: "The order has been placed and stock is updated.",
            icon: "success",
            timer: 2500, // තත්පර 2.5ක් පෙන්වනවා
            showConfirmButton: false
        }).then(() => {
            // Alert එකට පස්සේ URL එකෙන් success කියන කෑල්ල අයින් කරනවා (පිටුව රිෆ්‍රෙෂ් කරද්දි ආයෙත් alert එක එන එක නවත්තන්න)
            urlParams.delete('success');
            const newUrl = window.location.pathname + '?' + urlParams.toString();
            window.history.replaceState(null, null, newUrl);
        });
    }
</script>

</body>
</html>