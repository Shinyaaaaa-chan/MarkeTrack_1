<?php
session_start();
include 'db_connection.php';
include 'sidebar.php';

// Handle approval or rejection
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'approve') {
        $status = 'approved';
        $approved_at = date('Y-m-d H:i:s');
    } elseif ($action === 'reject') {
        $status = 'rejected';
        $approved_at = NULL;
    }

    $stmt = $conn->prepare("UPDATE promotion SET status=?, approved_at=? WHERE id=?");
    $stmt->bind_param("ssi", $status, $approved_at, $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Promotions</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-primary">Pending Promotions for Approval</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Discount (%)</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM promotion WHERE status = 'pending'");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['product_id'] ?></td>
                    <td><?= $row['discount_percentage'] ?>%</td>
                    <td><?= $row['start_date'] ?></td>
                    <td><?= $row['end_date'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
