<?php
session_start();
include 'db_connection.php';

if (!isset($_GET['variation_id'])) {
    echo "No variation selected.";
    exit;
}

$variationId = $_GET['variation_id'];

// Fetch variation info
$variationStmt = $conn->prepare("SELECT p.name, pv.flavor, pv.pack_size FROM product_variations pv JOIN products p ON pv.product_id = p.id WHERE pv.id = ?");
$variationStmt->bind_param("i", $variationId);
$variationStmt->execute();
$variationResult = $variationStmt->get_result()->fetch_assoc();

// Fetch batches
$batchStmt = $conn->prepare("SELECT * FROM stock_batches WHERE variation_id = ?");
$batchStmt->bind_param("i", $variationId);
$batchStmt->execute();
$batches = $batchStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Batches</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Batch Details for <?= htmlspecialchars($variationResult['name']) ?> - <?= htmlspecialchars($variationResult['flavor']) ?> / <?= htmlspecialchars($variationResult['pack_size']) ?></h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Batch ID</th>
                <th>Stock</th>
                <th>Expiration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($batch = $batches->fetch_assoc()): ?>
                <tr>
                    <td><?= $batch['id'] ?></td>
                    <td><?= $batch['stock'] ?></td>
                    <td><?= $batch['expiration_date'] ?></td>
                    <td>
                        <a href="update_inventory.php?batch_id=<?= $batch['id'] ?>&variation_id=<?= $variationId ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_batch.php?batch_id=<?= $batch['id'] ?>&variation_id=<?= $variationId ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this batch?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h4>Add New Batch</h4>
    <form method="POST" action="add_stock_batch.php">
        <input type="hidden" name="variation_id" value="<?= $variationId ?>">
        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Expiration Date</label>
            <input type="date" name="expiration" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Batch</button>
    </form>
</body>
</html>
