<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();

include 'db_connection.php';
include 'sidebar.php';

// Fetch product variations
$sql = "SELECT pv.id, p.name AS product_name, pv.flavor, pv.pack_size, pv.price_case, pv.stock
        FROM product_variations pv
        JOIN products p ON pv.product_id = p.id";
$result = $conn->query($sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $variation_id = $_POST['variation_id'];

    // Get current price & stock
    $stmt = $conn->prepare("SELECT price_case, stock FROM product_variations WHERE id = ?");
    $stmt->bind_param("i", $variation_id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $current_price = $data['price_case'];
    $stock = $data['stock'];

    // Get total sales in last month
    $stmt2 = $conn->prepare("SELECT SUM(quantity) AS total_sales 
                              FROM order_items oi
                              JOIN orders o ON oi.order_id = o.id
                              WHERE oi.variation_id = ? 
                              AND o.status = 'completed'
                              AND o.order_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)");
    $stmt2->bind_param("i", $variation_id);
    $stmt2->execute();
    $weekly_sales = $stmt2->get_result()->fetch_assoc()['total_sales'] ?? 0;

    // Pricing logic
    $new_price = $current_price;
    if ($weekly_sales > 50 && $stock < 100) {
        $new_price *= 1.15; // High demand + low stock → increase
    } elseif ($weekly_sales < 10 && $stock > 500) {
        $new_price *= 0.90; // Low demand + high stock → decrease
    }

    // Update price
    $stmt3 = $conn->prepare("UPDATE product_variations SET price_case = ? WHERE id = ?");
    $stmt3->bind_param("di", $new_price, $variation_id);
    $stmt3->execute();

    $success = "✅ Price updated based on demand and inventory!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Apply Dynamic Pricing</title>
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
/* General Page Styling */
body {
    background-color: #f4f4f9;
    font-family: 'Segoe UI', sans-serif;
}

/* Card Styling */
.pricing-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    background: #fff;
}

.pricing-card .card-header {
    background: linear-gradient(135deg, #6a11cb, #a044ff);
    color: #fff;
    border-radius: 15px 15px 0 0;
    text-align: center;
    padding: 1rem;
    font-size: 1.2rem;
    font-weight: bold;
}

.pricing-card .card-body {
    padding: 2rem;
}

/* Form Styling */
.form-label {
    font-weight: 600;
    color: #555;
}

.form-select {
    border-radius: 10px;
    padding: 10px;
}

/* Button Styling */
.btn-apply {
    background: linear-gradient(135deg, #6a11cb, #a044ff);
    border: none;
    border-radius: 10px;
    padding: 10px;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease-in-out;
}

.btn-apply:hover {
    transform: scale(1.02);
    background: linear-gradient(135deg, #5a0eb1, #8b35d9);
}
</style>
</head>
<body id="page-top">

<!-- Main Content -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content" class="container-fluid">

        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="m-0"><i class="fas fa-tags me-2"></i>Apply Dynamic Pricing</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?= $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="dynamicpricing.php">
                            <div class="mb-3">
                                <label for="variation_id" class="form-label fw-bold">Select Product Variation</label>
                                <select name="variation_id" id="variation_id" class="form-select" required>
                                    <option value="" disabled selected>Choose a product variation</option>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <option value="<?= $row['id'] ?>">
                                            <?= $row['product_name'] ?> - <?= $row['flavor'] ?> (<?= $row['pack_size'] ?>) — ₱<?= number_format($row['price_case'], 2) ?> | Stock: <?= $row['stock'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sync-alt me-2"></i>Apply Auto Pricing
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
