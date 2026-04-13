<?php
session_start();
require('includes/db_connect.php');

// Admin කෙනෙක් නෙවෙයි නම් මුල් පිටුවට හරවා යැවීම
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Order Status එක Update කිරීම
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_sql = "UPDATE orders SET status='$new_status' WHERE id='$order_id'";
    if (mysqli_query($conn, $update_sql)) {
        $success_msg = "Order #$order_id status updated to $new_status!";
    }
}

include('includes/header.php');
?>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customer Orders</h2>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <?php if(isset($success_msg)) { echo "<div class='alert alert-success'>$success_msg</div>"; } ?>

    <div class="card shadow-sm p-4 border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Shipping Address</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fetch_orders = "SELECT * FROM orders ORDER BY id DESC";
                    $result = mysqli_query($conn, $fetch_orders);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td><span class='badge bg-primary fs-6'>#{$row['id']}</span></td>";
                            
                            $name = isset($row['full_name']) ? $row['full_name'] : (isset($row['name']) ? $row['name'] : 'N/A');
                            echo "<td>{$name}</td>";
                            
                            $address = isset($row['address']) ? $row['address'] : (isset($row['shipping_address']) ? $row['shipping_address'] : 'N/A');
                            echo "<td class='text-start'>{$address}</td>";
                            
                            $total = isset($row['total_amount']) ? $row['total_amount'] : 0;
                            echo "<td class='fw-bold text-success'>$" . number_format((float)$total, 2) . "</td>";
                            
                            $date = isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : 'N/A';
                            echo "<td>{$date}</td>";
                            
                            // Status එක පෙන්නනවා (වර්ණවත් Badge එකකින්)
                            $current_status = isset($row['status']) ? $row['status'] : 'Pending';
                            $badge_color = ($current_status == 'Pending') ? 'bg-warning text-dark' : (($current_status == 'Shipped') ? 'bg-info text-dark' : 'bg-success');
                            echo "<td><span class='badge $badge_color'>$current_status</span></td>";
                            
                            // Status එක වෙනස් කරන Form එක
                            echo "<td>
                                    <form action='admin_orders.php' method='POST' class='d-flex align-items-center justify-content-center'>
                                        <input type='hidden' name='order_id' value='{$row['id']}'>
                                        <select name='status' class='form-select form-select-sm me-2' style='width: auto;'>
                                            <option value='Pending' " . ($current_status == 'Pending' ? 'selected' : '') . ">Pending</option>
                                            <option value='Shipped' " . ($current_status == 'Shipped' ? 'selected' : '') . ">Shipped</option>
                                            <option value='Delivered' " . ($current_status == 'Delivered' ? 'selected' : '') . ">Delivered</option>
                                        </select>
                                        <button type='submit' name='update_status' class='btn btn-sm btn-outline-primary'>Update</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-muted py-4'>No orders have been placed yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>