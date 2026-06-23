<?php
session_start();
include('db_connection.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // First delete variations related to the product
    $delete_variations = "DELETE FROM product_variations WHERE product_id = $product_id";
    mysqli_query($conn, $delete_variations);

    // Now delete the product
    $delete_product = "DELETE FROM products WHERE id = $product_id";
    mysqli_query($conn, $delete_product);

    header("Location: products.php");
    exit();
}
?>
