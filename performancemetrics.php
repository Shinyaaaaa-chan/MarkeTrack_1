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
    <title>Performance Metrics - MarkeTrack</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="vendor/chart.js/Chart.min.js"></script>
</head>
<body id="page-top">



    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

              
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Notifications -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="badge badge-danger badge-counter">3+</span>
                        </a>
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Alerts Center</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>New report available!</div>
                            </a>
                        </div>
                    </li>

                    <!-- User Profile -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Profile</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
<div class="container">
    <h1 class="my-4 text-primary">Performance Metrics</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card chart-container mb-4">
                <h5 class="card-header">Sales Trends</h5>
                <div class="card-body">
                    <canvas id="salesTrends"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card chart-container mb-4">
                <h5 class="card-header">Revenue Growth</h5>
                <div class="card-body">
                    <canvas id="revenueGrowth"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card chart-container mb-4">
                <h5 class="card-header">Profit Margins</h5>
                <div class="card-body">
                    <canvas id="profitMargins"></canvas>
                </div>
            </div>
        </div>

       
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    // Sales Trends - Line Chart
    const salesCtx = document.getElementById('salesTrends').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales',
                data: [1200, 1900, 3000, 2500, 3200, 4000],
                borderColor: '#4caf50',
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // Revenue Growth - Bar Chart
    const revenueCtx = document.getElementById('revenueGrowth').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'Revenue (in $)',
                data: [5000, 7000, 8000, 9000],
                backgroundColor: ['#3498db', '#9b59b6', '#f1c40f', '#e74c3c'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // Profit Margins - Pie Chart
    const profitCtx = document.getElementById('profitMargins').getContext('2d');
    new Chart(profitCtx, {
        type: 'pie',
        data: {
            labels: ['Product A', 'Product B', 'Product C'],
            datasets: [{
                label: 'Profit Margins',
                data: [300, 500, 200],
                backgroundColor: ['#2ecc71', '#e67e22', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // Conversion Rates - Doughnut Chart
    const conversionCtx = document.getElementById('conversionRates').getContext('2d');
    new Chart(conversionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Organic', 'Paid Ads', 'Referral', 'Direct'],
            datasets: [{
                label: 'Conversion Rates',
                data: [40, 25, 20, 15],
                backgroundColor: ['#1abc9c', '#f39c12', '#3498db', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
</script>

</body>
</html>