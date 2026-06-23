<?php
ini_set('session.gc_maxlifetime', 86400); // 24 hours in seconds
session_set_cookie_params(86400);         // 24 hours in seconds
session_start();

include 'db_connection.php'; // Ensure this path is correct
include 'sidebar.php';

$result = $conn->query("SELECT * FROM customers");

// Calendar logic
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

$monthName = date('F', mktime(0, 0, 0, $month, 1, $year));
$firstDay = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDay);
$startDayOfWeek = date('w', $firstDay);

$calendar = [];
for ($i = 0; $i < $startDayOfWeek; $i++) {
    $calendar[] = "";
}
for ($i = 1; $i <= $daysInMonth; $i++) {
    $calendar[] = $i;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers - MarkeTrack</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .card {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        h3 {
            font-weight: bold;
            color: #007bff;
        }

        @media (max-width: 768px) {
            h3 {
                font-size: 1.5rem;
                text-align: center;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .table {
                font-size: 0.9rem;
            }

            .table thead {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                overflow-x: auto;
            }

            .container-fluid {
                padding: 0 1rem;
            }

            .card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">
    <div class="card shadow p-4">
        <h3 class="mb-4">Registered Customers</h3>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Assistant Brand Manager'): ?>
        <div class="mb-3">
            <a href="add_customer.php" class="btn btn-primary shadow-sm">
                <i class="fas fa-user-plus mr-2"></i> Add Customer
            </a>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Store Name</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                        <td><?= htmlspecialchars($row['store_name']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['contact_number']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div> 
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>

</body>
</html>