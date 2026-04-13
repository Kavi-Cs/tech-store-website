<?php
session_start();
require('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    
    // Customer විස්තර අල්ලගැනීම
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = "In-Store Purchase"; // කඩේට ඇවිත් ගන්න නිසා ඇඩ්‍රස් එකක් නෑ
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
    
    // Admin / Cashier ගේ ID එක (දැනට 1 කියලා දාමු)
    $user_id = 1;

    // 1. Orders table එකට ඇතුළත් කිරීම
    $status = 'completed'; // කඩේදිම බඩු දෙන නිසා කෙලින්ම completed වෙනවා
    
    $order_sql = "INSERT INTO orders (user_id, customer_name, address, phone, total_amount, payment_method, status) 
                  VALUES ('$user_id', '$customer_name', '$address', '$phone', '$total_amount', '$payment_method', '$status')";
    
    if (mysqli_query($conn, $order_sql)) {
        $order_id = mysqli_insert_id($conn);

        // 2. Order Items එකින් එක ඇතුළත් කිරීම සහ Stock එක අඩු කිරීම
        $product_ids = $_POST['product_id'];
        $quantities = $_POST['quantity'];
        $prices = $_POST['price'];

        for ($i = 0; $i < count($product_ids); $i++) {
            $p_id = mysqli_real_escape_string($conn, $product_ids[$i]);
            $qty = mysqli_real_escape_string($conn, $quantities[$i]);
            $price = mysqli_real_escape_string($conn, $prices[$i]);

            // 2.1 Order Items වගුවට ඇතුළත් කිරීම
            $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$order_id', '$p_id', '$qty', '$price')";
            mysqli_query($conn, $item_sql);

            // 2.2 අදාළ භාණ්ඩයේ Stock එක අඩු කිරීම
            $update_stock = "UPDATE products SET stock = stock - $qty WHERE id = '$p_id'";
            mysqli_query($conn, $update_stock);
        }

        // 3. ලස්සනට Print වෙන්න Invoice එකට යවනවා (Success Alert එක පෙන්නන්න URL එකට &success=true දාලා තියෙනවා)
        header("Location: invoice.php?order_id=" . $order_id . "&success=true");
        exit();
    } else {
        echo "Error saving POS bill: " . mysqli_error($conn);
    }
} else {
    // බඩු මුකුත් නැතුව සබ්මිට් කළොත් පරණ බෝරින් Alert එක වෙනුවට admin_pos.php එකට හරවලා යවනවා
    header("Location: admin_pos.php?msg=empty");
    exit();
}
?>