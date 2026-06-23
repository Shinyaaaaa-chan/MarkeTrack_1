<?php
// --- Session setup ---
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();

require_once 'db_connection.php';
include 'sidebar.php';

// --- Redirect to login if not logged in ---
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Role-based access for order statuses ---
$user_role = $_SESSION['role'] ?? 'guest';
$role_status_access = [
    'Assistant Brand Manager' => ['pending', 'processing', 'declined', 'completed', 'cancelled'],
    'Brand Manager' => ['processing', 'completed'],
    'Trade And Marketing Team' => ['processing', 'completed'],
    'Merchandising Marketing Team' => ['processing', 'completed'],
    'Logistics' => ['processing', 'completed', 'cancelled'] // ✅ Added here
];

$allowed_statuses = $role_status_access[$user_role] ?? ['processing', 'completed'];
$status_filter = isset($_GET['status']) && in_array($_GET['status'], $allowed_statuses)
    ? $_GET['status']
    : $allowed_statuses[0];

// --- Assistant Brand Manager actions ---
if ($user_role === 'Assistant Brand Manager') {

    // Approve Order
    if (isset($_POST['approve'])) {
        $order_id = (int)$_POST['order_id'];

        try {
            $conn->begin_transaction();

            // Update order status
            $stmt = $conn->prepare("UPDATE orders SET status = 'processing' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();

            // Fetch order items
            $stmt_items = $conn->prepare("SELECT variation_id, quantity FROM order_items WHERE order_id = ?");
            $stmt_items->bind_param("i", $order_id);
            $stmt_items->execute();
            $result_items = $stmt_items->get_result();

            // Update stock batches
            while ($row = $result_items->fetch_assoc()) {
                $variation_id = $row['variation_id'];
                $quantity = $row['quantity'];

                $stmt_batches = $conn->prepare("SELECT id, stock FROM stock_batches WHERE variation_id = ? ORDER BY date_added ASC");
                $stmt_batches->bind_param("i", $variation_id);
                $stmt_batches->execute();
                $result_batches = $stmt_batches->get_result();

                while ($quantity > 0 && $batch = $result_batches->fetch_assoc()) {
                    $batch_id = $batch['id'];
                    $batch_stock = $batch['stock'];

                    if ($batch_stock >= $quantity) {
                        $new_stock = $batch_stock - $quantity;
                        $stmt_update_batch = $conn->prepare("UPDATE stock_batches SET stock = ? WHERE id = ?");
                        $stmt_update_batch->bind_param("ii", $new_stock, $batch_id);
                        $stmt_update_batch->execute();
                        $quantity = 0;
                    } else {
                        $quantity -= $batch_stock;
                        $stmt_update_batch = $conn->prepare("UPDATE stock_batches SET stock = 0 WHERE id = ?");
                        $stmt_update_batch->bind_param("i", $batch_id);
                        $stmt_update_batch->execute();
                    }
                }
            }

            $conn->commit();
            header("Location: orders.php");
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    }

    // Decline Order
    if (isset($_POST['decline'])) {
        $order_id = (int)$_POST['order_id'];
        $stmt_decline = $conn->prepare("UPDATE orders SET status = 'declined' WHERE id = ?");
        $stmt_decline->bind_param("i", $order_id);
        $stmt_decline->execute();
        header("Location: orders.php");
        exit();
    }
}

// --- Logistics actions ---
if ($user_role === 'Logistics') {

    if (isset($_POST['delivered'])) {
        $order_id = (int)$_POST['order_id'];
        $stmt_delivered = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
        $stmt_delivered->bind_param("i", $order_id);
        $stmt_delivered->execute();
        header("Location: orders.php");
        exit();
    }

    // Cancel Order
    if (isset($_POST['cancel'])) {
        $order_id = (int)$_POST['order_id'];
        $stmt_cancel = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $stmt_cancel->bind_param("i", $order_id);
        $stmt_cancel->execute();
        header("Location: orders.php");
        exit();
    }
}

// --- Fetch orders by status ---
$stmt = $conn->prepare("
    SELECT 
        o.id AS order_id,
        o.order_date,
        o.status,
        pr.promo_title,
        GROUP_CONCAT(p.name SEPARATOR '|') AS product_names,
        GROUP_CONCAT(
            CASE 
                WHEN pv.flavor IS NOT NULL OR pv.pack_size IS NOT NULL 
                THEN CONCAT_WS(' - ', pv.flavor, pv.pack_size)
                ELSE 'Standard' 
            END SEPARATOR '|'
        ) AS variations,
        GROUP_CONCAT(oi.quantity SEPARATOR '|') AS quantities,
        GROUP_CONCAT(oi.price SEPARATOR '|') AS unit_prices,
        c.fullname,
        c.store_name,
        c.address
    FROM orders o
    INNER JOIN order_items oi ON o.id = oi.order_id
    INNER JOIN products p ON oi.product_id = p.id
    LEFT JOIN product_variations pv ON oi.variation_id = pv.id
    LEFT JOIN customers c ON o.user_id = c.id
    LEFT JOIN promotions pr ON o.promotion_id = pr.id
    WHERE o.status = ?
    GROUP BY o.id
    ORDER BY o.order_date ASC
");
$stmt->bind_param("s", $status_filter);

$orders = [];
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $product_names = explode('|', $row['product_names']);
            $variations = explode('|', $row['variations']);
            $quantities = explode('|', $row['quantities']);
            $unit_prices = explode('|', $row['unit_prices']);

            // --- Fixed array mismatch section ---
            $items = [];
            $max_count = max(
                count($product_names),
                count($variations),
                count($quantities),
                count($unit_prices)
            );

            for ($i = 0; $i < $max_count; $i++) {
                $product_name = $product_names[$i] ?? 'Unknown Product';
                $variation = $variations[$i] ?? 'Standard';
                $quantity = isset($quantities[$i]) ? (float)$quantities[$i] : 0;
                $unit_price = isset($unit_prices[$i]) ? (float)$unit_prices[$i] : 0;
                $total_price = $quantity * $unit_price;

                $items[] = [
                    'product_name' => $product_name,
                    'variation' => $variation,
                    'quantity' => $quantity,
                    'unit_price' => $unit_price,
                    'total_price' => $total_price
                ];
            }

            $orders[$row['order_id']] = [
                'order_date' => $row['order_date'],
                'status' => $row['status'],
                'promo_title' => $row['promo_title'],
                'fullname' => $row['fullname'],
                'store_name' => $row['store_name'],
                'address' => $row['address'],
                'items' => $items
            ];
        }
    }
} else {
    echo "Query failed: " . $stmt->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders</title>
    <link href="css/style.css" rel="stylesheet">
    <style>
         .main-container {
            max-width: 900px;
            margin: 40px auto 0 auto;
            padding: 0 12px 40px 12px;
            max-height: calc(100vh - 80px);
            overflow-y: auto;
        }
        h1 {
            text-align: center;
            color: #222;
            margin-bottom: 25px;
        }
        .status-filter {
            text-align: center;
            margin-bottom: 30px;
        }
        .status-filter a {
            display: inline-block;
            padding: 8px 26px;
            margin: 3px;
            border-radius: 20px;
            text-decoration: none;
            color: #007bff;
            background: #fff;
            border: 2px solid #007bff;
            font-weight: 500;
            transition: all 0.2s;
        }
        .status-filter a.active, .status-filter a:hover {
            background: #007bff;
            color: #fff;
        }
        .order-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            margin-bottom: 25px;
            padding: 24px 28px 18px 28px;
        }
        .order-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .status-badge {
            padding: 3px 14px;
            border-radius: 13px;
            font-size: 13px;
            color: #fff;
            font-weight: 500;
            background: #ffc107;
        }
        .status-badge.processing { background: #17a2b8; }
        .status-badge.declined { background: #6c757d; }
        .status-badge.completed { background: #28a745; }
        .status-badge.cancelled { background: #dc3545; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 13px;
        }
        th, td {
            padding: 12px 8px;
            border: none;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background: #f7f7f7;
            color: #444;
        }
        td {
            color: #222;
        }
        .order-actions {
            margin-top: 10px;
        }
        .action-btn {
            background-color: #4e73df;
            color: white;
            padding: 9px 22px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 7px;
            font-size: 15px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .action-btn.decline {
            background-color: #dc3545;
        }
        .action-btn:hover {
            background-color: #1cc88a;
        }
        .action-btn.decline:hover {
            background-color: #b71c1c;
        }
        @media (max-width: 700px) {
            .main-container { padding: 0 2vw; }
            .order-card { padding: 10px 4vw 10px 4vw; }
            th, td { font-size: 13px; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1>Orders</h1>
        <div class="status-filter">
            <?php foreach ($allowed_statuses as $status): ?>
                <a href="?status=<?= htmlspecialchars($status) ?>" class="<?= ($status === $status_filter) ? 'active' : '' ?>">
                    <?= ucfirst($status) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order_id => $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        Order #<?= htmlspecialchars($order_id) ?> (Date: <?= htmlspecialchars($order['order_date']) ?>)
                        <span class="status-badge <?= htmlspecialchars($order['status']) ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                    <div>
                        <strong>Customer:</strong> <?= htmlspecialchars($order['fullname'] ?? '') ?><br>
                        <strong>Store Name:</strong> <?= htmlspecialchars($order['store_name'] ?? '') ?><br>
                        <strong>Address:</strong> <?= htmlspecialchars($order['address'] ?? '') ?><br>
                        <?php if (!empty($order['promo_title'])): ?>
                            <div><strong>Applied Promotion:</strong> <?= htmlspecialchars($order['promo_title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Variation</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td><?= htmlspecialchars($item['variation']) ?></td>
                                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                                    <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                                    <td>₱<?= number_format($item['total_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if ($order['status'] === 'pending' && $user_role === 'Assistant Brand Manager'): ?>
                        <form method="post" class="order-actions">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>" />
                            <button type="submit" name="approve" class="action-btn">Approve</button>
                            <button type="submit" name="decline" class="action-btn decline">Decline</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($order['status'] === 'processing' && $user_role === 'Logistics'): ?>
                        <form method="post" class="order-actions">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>" />
                            <button type="submit" name="delivered" class="action-btn">Mark as Delivered</button>
                            <button type="submit" name="cancel" class="action-btn decline">Cancel Order</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            
            <p style="text-align:center;">No orders to manage.</p>
        <?php endif; ?>
        <script>
document.querySelectorAll('button[name="delivered"]').forEach(btn => {
    btn.addEventListener('click', e => {
        if (!confirm("Are you sure you want to mark this order as delivered?")) {
            e.preventDefault();
        }
    });
});

document.querySelectorAll('button[name="cancel"]').forEach(btn => {
    btn.addEventListener('click', e => {
        if (!confirm("Are you sure you want to cancel this order?")) {
            e.preventDefault();
        }
    });
});
</script>

    </div>
</body>
</html>
