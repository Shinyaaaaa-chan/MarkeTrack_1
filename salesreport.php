<?php  
ini_set('session.gc_maxlifetime', 86400); // 24 hours in seconds
session_set_cookie_params(86400);         // 24 hours in seconds

session_start();

include 'db_connection.php';
include 'sidebar.php';

$query = "
SELECT 
    o.id AS order_id,
    c.fullname AS customer_name,
    c.store_name,
    c.address,
    p.name AS product_name,
    pv.flavor,
    pv.pack_size,
    oi.quantity,
    o.total_price,
    o.order_date
FROM orders o
JOIN customers c ON o.user_id = c.id
JOIN order_items oi ON o.id = oi.order_id
JOIN product_variations pv ON oi.variation_id = pv.id
JOIN products p ON pv.product_id = p.id
WHERE o.status = 'completed'
ORDER BY o.order_date DESC
";

$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . $conn->error);
}



$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales Report - MarkeTrack</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- DataTables + Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- Custom Styles for Table -->
    <style>
        .card-body {
            padding: 1.5rem;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #f8f9fc;
            color: #4e73df;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 250px;
            height: 34px;
            padding-left: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        .dataTables_wrapper .dataTables_length select {
            height: 34px;
            padding-left: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        .export-buttons {
            padding: 10px;
            display: flex;
            justify-content: space-between;
        }
        .btn-export {
            background-color: #4e73df;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
        }
        .btn-export:hover {
            background-color: #2e59d9;
        }
    </style>
</head>

<body id="page-top">

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content" class="container-fluid mt-4">
        <h1 class="h3 mb-4 text-gray-800">Sales Report</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div id="exportButtons" class="export-buttons">
                    <!-- Export buttons will appear here -->
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="salesTable" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Store Name</th>
                                <th>Address</th>
                                <th>Product Name</th>
                                <th>Flavor</th>
                                <th>Pack/Size</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>".htmlspecialchars($row['order_id'])."</td>
                                        <td>".htmlspecialchars($row['customer_name'])."</td>
                                        <td>".htmlspecialchars($row['store_name'])."</td>
                                        <td>".htmlspecialchars($row['address'])."</td>
                                        <td>".htmlspecialchars($row['product_name'])."</td>
                                        <td>".htmlspecialchars($row['flavor'])."</td>
                                        <td>".htmlspecialchars($row['pack_size'])."</td>
                                        <td>".htmlspecialchars($row['quantity'])."</td>
                                        <td>₱".number_format($row['total_price'], 2)."</td>
                                        <td>".date("F j, Y", strtotime($row['order_date']))."</td>
                                    </tr>";
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
               

<!-- JS libraries -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    // Initialize DataTable without explicit columns definition
    var table = $('#salesTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                className: 'btn btn-primary',
                buttons: [
                    { extend: 'excelHtml5', title: 'Sales Report' },
                    { extend: 'csvHtml5', title: 'Sales Report' },
                    { 
                        extend: 'pdfHtml5', 
                        title: 'Sales Report', 
                        orientation: 'landscape', 
                        pageSize: 'A4',
                        customize: function (doc) {
                            doc.defaultStyle.alignment = 'center';
                            doc.styles.tableHeader.alignment = 'center';
                        }
                    },
                    { extend: 'print', title: 'Sales Report' }
                ]
            }
        ],
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
    });

    // Move the export buttons into the header container
    table.buttons().container().appendTo('#exportButtons');
});
</script>

<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>