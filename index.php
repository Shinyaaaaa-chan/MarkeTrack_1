<?php
ini_set('session.gc_maxlifetime', 86400); // 24 hours in seconds
session_set_cookie_params(86400);         // 24 hours in seconds
session_start();

include 'sidebar.php';
include 'db_connection.php';  

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch logged-in user data
$user_id = $_SESSION['user_id'];
$query = "SELECT fullname, role FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
$user = mysqli_fetch_assoc($result);

// Calendar setup
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
if ($month < 1) { $month = 12; $year--; }
elseif ($month > 12) { $month = 1; $year++; }
$monthName = date('F', mktime(0, 0, 0, $month, 1, $year));
$firstDay = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDay);
$startDayOfWeek = date('w', $firstDay);
$calendar = array_merge(array_fill(0, $startDayOfWeek, ""), range(1, $daysInMonth));

// Dashboard stats
$totalSales = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT SUM(total_price) AS total_sales FROM orders 
     WHERE MONTH(order_date) = MONTH(CURDATE()) 
     AND YEAR(order_date) = YEAR(CURDATE())"
))['total_sales'] ?? 0;

$demandRow = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT p.name, SUM(oi.quantity) AS total_sold 
     FROM order_items oi 
     JOIN products p ON oi.product_id = p.id 
     GROUP BY oi.product_id 
     ORDER BY total_sold DESC 
     LIMIT 1"
));
$topProduct = $demandRow['name'] ?? 'No data';
$topSold = $demandRow['total_sold'] ?? 0;

$lowStockCount = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) AS low_stock_count FROM product_variations WHERE stock <= 10"
))['low_stock_count'] ?? 0;

$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) AS total_orders FROM orders"
))['total_orders'] ?? 0;

// Forecast section
require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Regression\LeastSquares;

$salesDataByMonth = [
    '2022-01' => 1531, '2022-02' => 811, '2022-03' => 1235, '2022-04' => 1966,
    '2022-05' => 984, '2022-06' => 859, '2022-07' => 1713, '2022-08' => 807,
    '2022-09' => 983, '2022-10' => 670, '2022-11' => 1316, '2022-12' => 1164,
    '2023-01' => 1265, '2023-02' => 1367, '2023-03' => 1601, '2023-04' => 897,
    '2023-05' => 1181, '2023-06' => 1866, '2023-07' => 2153, '2023-08' => 877,
    '2023-09' => 1529, '2023-10' => 1731, '2023-11' => 1725, '2023-12' => 2233,
    '2024-01' => 1331, '2024-02' => 1317, '2024-03' => 769, '2024-04' => 1511,
    '2024-05' => 1711, '2024-06' => 1774, '2024-07' => 1983, '2024-08' => 2188,
    '2024-09' => 824, '2024-10' => 1356, '2024-11' => 1657, '2024-12' => 878,
];

// Prepare regression training data
$startDate = new DateTime('2022-01-01');
$months = [];
$sales = [];
foreach ($salesDataByMonth as $dateStr => $quantity) {
    $currentDate = new DateTime($dateStr . '-01');
    $interval = $startDate->diff($currentDate);
    $monthIndex = ($interval->y * 12) + $interval->m + 1;
    $months[] = $monthIndex;
    $sales[] = $quantity;
}
$samples = array_map(fn($m) => [$m], $months);

// Train and forecast
$regression = new LeastSquares();
$regression->train($samples, $sales);
$lastMonthIndex = max($months);
$forecast = [];
$forecastLabels = [];
$lastDate = new DateTime(array_key_last($salesDataByMonth) . '-01');
for ($i = 1; $i <= 3; $i++) {
    $forecast[] = round($regression->predict([$lastMonthIndex + $i]));
    $forecastLabels[] = $lastDate->modify("+1 month")->format("Y-m");
}
$allLabels = array_merge(array_keys($salesDataByMonth), $forecastLabels);

// Align forecast data with null padding for Chart.js
$forecastData = array_merge(array_fill(0, count($salesDataByMonth), null), $forecast);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MarkeTrack</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
</head>
<body id="page-top">

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

         <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-light topbar mb-4 static-top shadow">
      

             <!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">



<!-- Manager Name + User Icon -->
<li class="nav-item dropdown no-arrow ml-2" style="position: relative; display: flex; align-items: center;">
    <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="line-height: 1;">
        <?php echo isset($user['fullname']) ? htmlspecialchars($user['fullname']) : "User not found"; ?>
    </span>
    <a href="#" class="user-trigger" style="cursor: pointer; display: flex; align-items: center;">
        <i class="fas fa-user-circle fa-lg text-gray-600"></i>
    </a>

    <!-- Custom Dropdown Panel -->
    <div id="userPanel" class="dropdown-panel"
        style="display:none; position:absolute; top:40px; right:0; width:180px;
               background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.15);
               border-radius:8px; z-index:1000; padding:10px;">
        <a href="logout.php" style="display:block; padding:8px; text-decoration:none; color:#333;">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</li>

</ul>
    </nav>


        <!-- Main Content -->
        <div class="main-content">

            <!-- Welcome Section -->
            <div class="card p-4 mb-4">
                <h2>Hello, Welcome Back</h2>
                <p>Today's Date: <?php echo date('l, F j, Y'); ?></p>
            </div>

            <div class="row">

<!-- Total Sales This Month -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Sales (This Month)</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo number_format($totalSales, 2); ?></div>
        </div>
    </div>
</div>

<!-- Most In-Demand Product -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Most In-Demand Product</div>
            <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo $topProduct . " (" . $topSold . ")"; ?></div>
        </div>
    </div>
</div>

<!-- Low Stock Products -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Low Stock Products</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $lowStockCount; ?></div>
        </div>
    </div>
</div>

<!-- Total Orders -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Orders</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalOrders; ?></div>
        </div>
    </div>
</div>

</div>
       

          <!-- Demand Forecast Chart -->
          <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Demand Forecast vs Actual</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" width="100%" height="400"></canvas>
               

<script>



document.addEventListener("DOMContentLoaded", function(){
    const trigger = document.querySelector(".user-trigger");
    const panel = document.querySelector("#userPanel");

    trigger.addEventListener("click", function(e){
        e.preventDefault();
        panel.style.display = (panel.style.display === "block") ? "none" : "block";
    });

    document.addEventListener("click", function(e){
        if (!trigger.contains(e.target) && !panel.contains(e.target)) {
            panel.style.display = "none";
        }
    });
});
</script>
<script>
        var labels = <?php echo json_encode($allLabels); ?>;
        var actualSales = <?php echo json_encode(array_values($salesDataByMonth)); ?>;
        var forecastSales = <?php echo json_encode($forecastData); ?>;
        
        var ctx = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Actual Sales",
                        data: actualSales,
                        borderColor: "blue",
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: "Forecast Sales",
                        data: forecastSales,
                        borderColor: "red",
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.parsed.y === null) return null;
                                return context.dataset.label + ": " + context.parsed.y + " units";
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</script>
</body>
</html>
