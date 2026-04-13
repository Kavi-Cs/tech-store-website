<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require('includes/db_connect.php');
include('includes/header.php');

// 1. අද දවසේ ආදායම
$today_sql = "SELECT SUM(total_amount) as total FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'";
$today_result = mysqli_query($conn, $today_sql);
$today_sales = mysqli_fetch_assoc($today_result)['total'];
$today_sales = $today_sales ? $today_sales : 0;

// 2. මේ මාසයේ ආදායම
$month_sql = "SELECT SUM(total_amount) as total FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND status != 'cancelled'";
$month_result = mysqli_query($conn, $month_sql);
$month_sales = mysqli_fetch_assoc($month_result)['total'];
$month_sales = $month_sales ? $month_sales : 0;

// 3. මුළු ආදායම
$all_sql = "SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'";
$all_result = mysqli_query($conn, $all_sql);
$all_sales = mysqli_fetch_assoc($all_result)['total'];
$all_sales = $all_sales ? $all_sales : 0;

// 4. පසුගිය දින 7 සඳහා ප්‍රස්ථාර දත්ත (Chart Data) ලබා ගැනීම
$chart_sql = "SELECT DATE(created_at) as sale_date, SUM(total_amount) as daily_total 
              FROM orders 
              WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND status != 'cancelled'
              GROUP BY DATE(created_at) 
              ORDER BY DATE(created_at) ASC";
$chart_result = mysqli_query($conn, $chart_sql);

$dates = [];
$totals = [];

// දත්ත Array වලට දාගන්නවා
while($row = mysqli_fetch_assoc($chart_result)) {
    $dates[] = $row['sale_date'];
    $totals[] = $row['daily_total'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-5 mb-5" style="min-height: 60vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">📈 Sales & Revenue Report</h2>
        <a href="admin_dashboard.php" class="btn btn-outline-dark shadow-sm">Back to Dashboard</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-primary text-white text-center h-100">
                <div class="card-body py-4">
                    <h5 class="card-title text-uppercase mb-3">Today's Sales</h5>
                    <h2 class="fw-bold">Rs. <?php echo number_format($today_sales, 2); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-success text-white text-center h-100">
                <div class="card-body py-4">
                    <h5 class="card-title text-uppercase mb-3">This Month's Sales</h5>
                    <h2 class="fw-bold">Rs. <?php echo number_format($month_sales, 2); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-dark text-white text-center h-100">
                <div class="card-body py-4">
                    <h5 class="card-title text-uppercase mb-3">All-Time Total Sales</h5>
                    <h2 class="fw-bold">Rs. <?php echo number_format($all_sales, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-4 bg-white border-0 mb-5">
        <h4 class="mb-4 text-center fw-bold">Sales Overview (Last 7 Days)</h4>
        <canvas id="salesChart" height="100"></canvas>
    </div>

    <div class="card shadow-sm p-4 bg-white border-0">
        <h4 class="mb-3 border-bottom pb-2">Recent Transactions</h4>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Payment Method</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recent_sql = "SELECT * FROM orders WHERE status != 'cancelled' ORDER BY id DESC LIMIT 15";
                    $recent_result = mysqli_query($conn, $recent_sql);

                    if (mysqli_num_rows($recent_result) > 0) {
                        while ($row = mysqli_fetch_assoc($recent_result)) {
                            ?>
                            <tr>
                                <td><span class="badge bg-secondary">#<?php echo $row['id']; ?></span></td>
                                <td><?php echo date('Y-m-d h:i A', strtotime($row['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                <td class="text-end fw-bold text-success">Rs. <?php echo number_format($row['total_amount'], 2); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4'>No sales recorded yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // PHP වලින් හදාගත්ත දත්ත JavaScript වලට ගන්නවා
    const chartLabels = <?php echo json_encode($dates); ?>;
    const chartData = <?php echo json_encode($totals); ?>;

    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar', // ඔබට 'line' කියලා වෙනස් කරලත් බලන්න පුළුවන්
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Daily Sales (Rs.)',
                data: chartData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)', // ලා නිල් පාට
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5 // බාර් වල කොන් රවුම් කරනවා
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include('includes/footer.php'); ?>