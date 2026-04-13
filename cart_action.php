<?php
session_start();

// URL එකෙන් action එක සහ id එක ගන්නවා
$action = isset($_GET['action']) ? $_GET['action'] : '';
$product_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($action == 'add' && !empty($product_id)) {
    
    // Cart එකක් කලින් හැදිලා නැත්නම් අලුතින් හදනවා
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // භාණ්ඩය කලින් Cart එකේ තියෙනවද බලනවා
    if (isset($_SESSION['cart'][$product_id])) {
        // තිබ්බොත් Quantity එක 1 කින් වැඩි කරනවා
        $_SESSION['cart'][$product_id]++;
    } else {
        // නැත්නම් අලුතින් Cart එකට එකතු කරනවා
        $_SESSION['cart'][$product_id] = 1;
    }

    // ආයෙමත් Home Page එකටම හරවලා යවනවා
    header("Location: index.php");
    exit();
}
?>