<?php
session_start();
include 'db_connection.php';  // Database connection included
include 'sidebar.php';
// Your existing PHP code here...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demand Analysis - MarkeTrack</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="vendor/chart.js/Chart.min.js"></script>
</head>
<body id="page-top">



    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content" class="container mt-5">
            <h1 class="text-info">Demand Analysis</h1>

            <!-- Filter & Search -->
            <div class="d-flex justify-content-between mb-4">
                <input type="text" id="searchInput" class="form-control w-50" placeholder="Search products...">
                <select id="filterCategory" class="form-control w-25">
                    <option value="All">All Categories</option>
                    <option value="Snacks">Snacks</option>
                    <option value="Beverages">Beverages</option>
                </select>
                <button class="btn btn-primary" onclick="applyFilter()">Filter</button>
            </div>

            <!-- Side-by-Side Charts -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Product Demand Trends</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="demandChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Category Demand Distribution</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    // Mock Data - Replace with PHP & MySQL later
    const products = [
        { month: 'Jan', product: 'Choco Pie', category: 'Snacks', demand: 120 },
        { month: 'Feb', product: 'Choco Pie', category: 'Snacks', demand: 150 },
        { month: 'Mar', product: 'Choco Pie', category: 'Snacks', demand: 180 },
        { month: 'Apr', product: 'Coffee Joy', category: 'Beverages', demand: 100 },
        { month: 'May', product: 'Coffee Joy', category: 'Beverages', demand: 130 }
    ];

    // Demand Trends Chart
    const demandCtx = document.getElementById('demandChart').getContext('2d');
    new Chart(demandCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [
                {
                    label: 'Choco Pie',
                    data: [120, 150, 180, 0, 0],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2
                },
                {
                    label: 'Coffee Joy',
                    data: [0, 0, 0, 100, 130],
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderWidth: 2
                }
            ]
        }
    });

    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: ['Snacks', 'Beverages'],
            datasets: [{
                data: [450, 230],
                backgroundColor: ['#28a745', '#ffc107']
            }]
        }
    });

    // Filter Functionality
    function applyFilter() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('filterCategory').value;

        const filteredProducts = products.filter(p => 
            (p.product.toLowerCase().includes(search) || search === '') && 
            (category === 'All' || p.category === category)
        );

        console.log('Filtered Products:', filteredProducts);
        alert(`Filtered ${filteredProducts.length} products`);
    }
</script>

</body>
</html>
