<?php
include 'db_connection.php';

$date = $_GET['date'];
$dateEscaped = mysqli_real_escape_string($conn, $date);

$query = "SELECT COUNT(*) AS order_count FROM orders WHERE DATE(order_date) = '$dateEscaped'";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<p>Total Orders: {$row['order_count']}</p>";
} else {
    echo "<p>No orders found.</p>";
}

mysqli_close($conn);
?>
