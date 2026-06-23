<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Merchandising Marketing Team') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $variationId = $_POST['variation_id'];
    $stock = $_POST['stock'];
    $expiration = $_POST['expiration'];

    $stmt = $conn->prepare("INSERT INTO stock_batches (variation_id, stock, expiration_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $variationId, $stock, $expiration);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Batch stock added successfully.";
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: inventoryoverview.php?variation_id=$variationId");
    exit;
}
?>
