<?php 
ini_set('session.gc_maxlifetime', 86400); // 24 hours in seconds
session_set_cookie_params(86400);         // 24 hours in seconds
session_start();

include 'db_connection.php';
include 'sidebar.php';

$query = "
    SELECT 
        r.id AS rating_id, 
        r.order_id, 
        r.order_item_id, 
        r.user_id, 
        r.product_quality, 
        r.delivery_service, 
        r.overall_satisfaction, 
        r.created_at,
        o.order_date, 
        u.username AS customer_name
    FROM order_ratings r
    JOIN orders o ON r.order_id = o.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at ASC
";

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$ratings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ratings | MarkeTrack</title>
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            background: #f4f6fb;
            color: #222;
        }

        .ratings-container {
            position: absolute;
            top: 0;
            left: 250px; /* Same sidebar offset */
            right: 0;
            bottom: 0;
            overflow-y: scroll;
            padding: 2.5rem 2rem;
        }

        .ratings-container h1 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 2rem;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .rating-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(220, 53, 69, 0.08), 0 1.5px 4px rgba(220, 53, 69, 0.04);
            margin-bottom: 2rem;
            padding: 2rem;
            border: 1px solid #f0d4d4;
            transition: 0.2s ease-in-out;
        }

        .rating-card:hover {
            box-shadow: 0 8px 32px rgba(220, 53, 69, 0.12), 0 2px 8px rgba(220, 53, 69, 0.06);
            transform: translateY(-2px);
        }

        .rating-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .rating-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: #dc3545;
        }

        .rating-header small {
            color: #888;
            font-size: 0.95rem;
        }

        .rating-stars {
            color: #dc3545;
            font-size: 1.5rem;
            letter-spacing: 1.5px;
        }

        .rating-metrics {
            display: flex;
            gap: 1.2rem;
            margin-top: 0.5rem;
            margin-bottom: 1.2rem;
        }

        .metric-item {
            flex: 1;
            background: #fdf1f1;
            border-radius: 10px;
            text-align: center;
            padding: 1.1rem 0.5rem;
            border: 1px solid #f4cfcf;
        }

        .metric-label {
            font-weight: 500;
            color: #dc3545;
            margin-bottom: 0.3rem;
        }

        .metric-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: #dc3545;
        }

        .no-ratings {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #888;
            font-size: 1.1rem;
            border: 1px dashed #ccc;
        }

        @media (max-width: 700px) {
            .ratings-container {
                left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="ratings-container">
        <h1><i class="fas fa-star-half-alt"></i> Customer Ratings</h1>

        <?php if (empty($ratings)): ?>
            <div class="no-ratings">No ratings available yet.</div>
        <?php else: ?>
            <?php foreach ($ratings as $rating): ?>
                <div class="rating-card">
                    <div class="rating-header">
                        <div>
                            <h3>Order #<?= htmlspecialchars($rating['order_id']) ?></h3>
                            <small>
                                <?= 
                                    ($rating['created_at'] && $rating['created_at'] !== '0000-00-00 00:00:00') 
                                        ? date('M d, Y h:i A', strtotime($rating['created_at'])) 
                                        : 'Date not available'; 
                                ?>
                            </small>
                        </div>
                        <?php
                            $avg_rating = round(((int)$rating['product_quality'] + (int)$rating['delivery_service'] + (int)$rating['overall_satisfaction']) / 3);
                        ?>
                        <div class="rating-stars">
                            <?= str_repeat('<i class="fas fa-star"></i>', $avg_rating) ?>
                            <?= str_repeat('<i class="far fa-star"></i>', 5 - $avg_rating) ?>
                            <small style="font-size: 0.9rem; color: #555;">(<?= $avg_rating ?>/5)</small>
                        </div>
                    </div>

                    <div class="rating-metrics">
                        <div class="metric-item">
                            <div class="metric-label">Product Quality</div>
                            <div class="metric-value"><?= htmlspecialchars($rating['product_quality']) ?>/5</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Delivery Service</div>
                            <div class="metric-value"><?= htmlspecialchars($rating['delivery_service']) ?>/5</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Overall Satisfaction</div>
                            <div class="metric-value"><?= htmlspecialchars($rating['overall_satisfaction']) ?>/5</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>