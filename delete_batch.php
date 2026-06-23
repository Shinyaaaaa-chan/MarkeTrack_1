<?php
session_start();
include 'db_connection.php';

if (!isset($_GET['batch_id']) || !isset($_GET['variation_id'])) {
    $_SESSION['error'] = "Invalid request.";
    header('Location: delete_batch.php?variation_id=' . $_GET['variation_id']);
    exit;
}

$batchId = $_GET['batch_id'];
$variationId = $_GET['variation_id'];

// Prepare and execute the deletion query
$deleteStmt = $conn->prepare("DELETE FROM stock_batches WHERE id = ?");
$deleteStmt->bind_param("i", $batchId);

if ($deleteStmt->execute()) {
    $_SESSION['success'] = "Batch deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete the batch.";
}

header('Location: inventoryoverview.php?variation_id=' . $variationId);
exit;
?>
