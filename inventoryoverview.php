<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();

include 'db_connection.php';
include 'sidebar.php';

$sql = "SELECT 
            pv.id,
            p.name,
            pv.flavor,
            pv.pack_size,
            COALESCE(SUM(sb.stock), 0) AS total_stock,
            MIN(sb.expiration_date) AS nearest_expiration
        FROM product_variations pv
        JOIN products p ON p.id = pv.product_id
        LEFT JOIN stock_batches sb ON sb.variation_id = pv.id
        GROUP BY pv.id, p.name, pv.flavor, pv.pack_size";


$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "No products found.";
}

$isMerchandisingMarketingTeam = isset($_SESSION['role']) && $_SESSION['role'] === 'Merchandising Marketing Team';
$isBrandManager = isset($_SESSION['role']) && $_SESSION['role'] === 'Brand Manager';
$isAssistantBrandManager = isset($_SESSION['role']) && $_SESSION['role'] === 'Assistant Brand Manager';
$isTradeAndMarketingTeam = isset($_SESSION['role']) && $_SESSION['role'] === 'Trade and Marketing Team';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Overview</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body id="page-top">
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content" class="container mt-5">
            <h1 class="text-primary">Inventory Overview</h1>

            <div class="card shadow mb-4">
                
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Product</th>
                                <th>Flavor/Pack Size</th>
                                <th>Total Stock</th>
                                <th>Nearest Expiration</th>
                                <th>Status</th>
                                <?php if ($isMerchandisingMarketingTeam): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['flavor']) ?> / <?= htmlspecialchars($product['pack_size']) ?></td>
                                    <td><?= (int)$product['total_stock'] ?></td>
                                    <td><?= $product['nearest_expiration'] ? date('Y-m-d', strtotime($product['nearest_expiration'])) : '—' ?></td>
                                    <td>
                                        <?php if ($product['total_stock'] >= 100): ?>
                                            <span class="badge badge-success">In Stock</span>
                                        <?php elseif ($product['total_stock'] >= 11): ?>
                                            <span class="badge badge-warning">Low Stock</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Out of Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($isMerchandisingMarketingTeam): ?>
                                        <td>
                                           
                                            <a href="view_batches.php?variation_id=<?= $product['id'] ?>" class="btn btn-sm btn-info">View Batches</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
