<?php

$current_page = basename($_SERVER['PHP_SELF']);// Make sure the session is started

// Get the user role from session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : ''; 
?>
<body id="page-top">

<div id="wrapper">
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="sidebar-brand-text mx-3">MarkeTrack</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    
    <!-- Nav Item - Dashboard (Visible for all roles) -->
    <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Products (Visible for Brand Manager) -->
        <li class="nav-item <?= ($current_page == 'products.php') ? 'active' : '' ?>">
            <a class="nav-link" href="products.php">
                <i class="fas fa-box"></i>
                <span>Products</span>
            </a>
        </li>

    <!-- Nav Item - Inventory Overview (view only for Brand Manager and Merchandising Marketing Team) -->
   
        <li class="nav-item <?= ($current_page == 'inventoryoverview.php') ? 'active' : '' ?>">
            <a class="nav-link" href="inventoryoverview.php">
                <i class="fas fa-chart-line"></i>
                <span>Inventory Overview</span>
            </a>
        </li>
   


    <!-- Nav Item - Promotion Management (approve only for Brand Manager, create & track for Trade & Marketing Team, view only for Merchandising Marketing Team) -->
    
        <li class="nav-item <?= ($current_page == 'promotionmanagement.php') ? 'active' : '' ?>">
            <a class="nav-link" href="promotionmanagement.php">
                <i class="fas fa-bullhorn"></i>
                <span>Promotion Management</span>
            </a>
        </li>
  

    <!-- Nav Item - Dynamic Pricing (Visible for Brand Manager) -->
    <?php if ($role == 'Brand Manager'): ?>
        <li class="nav-item <?= ($current_page == 'dynamicpricing.php') ? 'active' : '' ?>">
            <a class="nav-link" href="dynamicpricing.php">
                <i class="fas fa-cogs"></i>
                <span>Dynamic Pricing</span>
            </a>
        </li>
    <?php endif; ?>


    <!-- Nav Item - Sales Report (view only for Brand Manager) -->
        <li class="nav-item <?= ($current_page == 'salesreport.php') ? 'active' : '' ?>">
            <a class="nav-link" href="salesreport.php">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Sales Report</span>
            </a>
        </li>


    <!-- Nav Item - Add Account (Visible for Brand Manager only) -->
<?php if ($role == 'Brand Manager'): ?>
    <li class="nav-item <?= ($current_page == 'AddAccount.php') ? 'active' : '' ?>">
        <a class="nav-link" href="AddAccount.php">
            <i class="fas fa-user-plus"></i>
            <span>Add Account</span>
        </a>
    </li>
<?php endif; ?>


    <!-- Nav Item - Customers (approve registrations for Brand Manager, view only for Assistant Brand Manager) -->
    
        <li class="nav-item <?= ($current_page == 'customer.php') ? 'active' : '' ?>">
            <a class="nav-link" href="customer.php">
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>
        </li>
 

    <!-- Nav Item - Orders (approve for Brand Manager, review & approve for Assistant Brand Manager, process for Trade & Marketing Team) -->

        <li class="nav-item <?= ($current_page == 'orders.php') ? 'active' : '' ?>">
            <a class="nav-link" href="orders.php">
                <i class="fas fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
        </li>
   

   
        <li class="nav-item <?= ($current_page == 'ratings.php') ? 'active' : '' ?>">
            <a class="nav-link" href="ratings.php">
                <i class="fas fa-star"></i>
                <span>Ratings</span>
            </a>
        </li>
    


</ul>
