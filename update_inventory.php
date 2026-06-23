<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Merchandising Marketing Team') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['batch_id'])) {
    // Display form
    $batchId = $_GET['batch_id'];
    $variationId = $_GET['variation_id'];

    $stmt = $conn->prepare("SELECT * FROM stock_batches WHERE id = ?");
    $stmt->bind_param("i", $batchId);
    $stmt->execute();
    $batch = $stmt->get_result()->fetch_assoc();
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Batch</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body class="container mt-5">
        <h2>Edit Batch ID: <?= $batchId ?></h2>
        <form method="POST" action="update_inventory.php">
            <input type="hidden" name="batch_id" value="<?= $batchId ?>">
            <input type="hidden" name="variation_id" value="<?= $variationId ?>">
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" value="<?= $batch['stock'] ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Expiration Date</label>
                <input type="date" name="expiration_date" value="<?= $batch['expiration_date'] ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Update Batch</button>
        </form>
    </body>
    </html>

<?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batchId = $_POST['batch_id'];
    $variationId = $_POST['variation_id'];
    $stock = $_POST['stock'];
    $expiration = $_POST['expiration_date'];

    $stmt = $conn->prepare("UPDATE stock_batches SET stock = ?, expiration_date = ? WHERE id = ?");
    $stmt->bind_param("isi", $stock, $expiration, $batchId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Batch updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating batch.";
    }

    header("Location: view_batches.php?variation_id=$variationId");
    exit;
}
?>
