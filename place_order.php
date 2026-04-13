<?php
session_start();
require('includes/db_connect.php');

// Form එකෙන් දත්ත ඇවිත්ද කියලා බලනවා
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Cart එක හිස් නම් checkout එකටම හරවලා යවනවා Error එකක් එක්ක
    if (empty($_SESSION['cart'])) {
        header("Location: checkout.php?error=empty_cart");
        exit();
    }
    
    // 1. Form එකේ දත්ත විචල්‍ය (Variables) වලට ගැනීම
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // අලුත් Payment Method එක අල්ලගන්නවා
    $payment_method_input = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    // Koko නම් Installment ගාණත් එක්කම නම හදනවා
    if ($payment_method_input == 'Koko' && isset($_POST['koko_installments'])) {
        $koko_plan = mysqli_real_escape_string($conn, $_POST['koko_installments']);
        $payment_method = "Koko (" . $koko_plan . " Installments)";
    } else {
        $payment_method = $payment_method_input;
    }

    // Guest User ගේ ID එක 
    $user_id = 1;

    // 2. Cart එකේ මුළු මුදල (Total Amount) ගණනය කිරීම
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT price FROM products WHERE id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $total_amount += $row['price'] * $quantity;
        }
    }

    // COD නම් Delivery Charge එක විදිහට රු. 500ක් එකතු කරනවා
    if ($payment_method_input == 'COD') {
        $total_amount += 500;
    }

    // 3. Orders වගුවට ප්‍රධාන විස්තරය ඇතුළත් කිරීම
    $order_sql = "INSERT INTO orders (user_id, customer_name, address, phone, total_amount, payment_method, status) 
                  VALUES ('$user_id', '$name', '$address', '$phone', '$total_amount', '$payment_method', 'pending')";
    
    if (mysqli_query($conn, $order_sql)) {
        // අලුතින් හැදුණු Order එකේ ID එක ලබා ගැනීම
        $order_id = mysqli_insert_id($conn);

        // 4. Order Items වගුවට භාණ්ඩ එකින් එක ඇතුළත් කිරීම සහ Stock එක අඩු කිරීම
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $item_sql = "SELECT price FROM products WHERE id = '$product_id'";
            $item_result = mysqli_query($conn, $item_sql);
            if ($item_row = mysqli_fetch_assoc($item_result)) {
                $price = $item_row['price'];
                
                // 4.1 Order Items වගුවට ඇතුළත් කිරීම
                $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES ('$order_id', '$product_id', '$quantity', '$price')";
                mysqli_query($conn, $insert_item);

                // 4.2 අදාළ භාණ්ඩයේ Stock එක අඩු කිරීම
                $update_stock = "UPDATE products SET stock = stock - $quantity WHERE id = '$product_id'";
                mysqli_query($conn, $update_stock);
            }
        }

        // 5. සාර්ථකව Order එක දැම්මට පස්සේ Cart එක හිස් කිරීම
        unset($_SESSION['cart']);

        // 6. Invoice පිටුවට යවනවා &success=true එකතු කරලා (එතකොට Invoice එකේදී SweetAlert එක පෙන්වනවා!)
        header("Location: invoice.php?order_id=" . $order_id . "&success=true");
        exit();

    } else {
        // Database අවුලක් ගිහින් Order එක සේව් වුණේ නැත්නම් ආපහු checkout එකට යවනවා error එකක් එක්ක
        header("Location: checkout.php?error=payment_failed");
        exit();
    }
} else {
    // කෙලින්ම මේ පිටුවට ආවොත් මුල් පිටුවට හරවා යවනවා
    header("Location: index.php");
    exit();
}
?>