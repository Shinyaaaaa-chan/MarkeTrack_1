<?php
ini_set('session.gc_maxlifetime', 86400); // 24 hours in seconds
session_set_cookie_params(86400);         // 24 hours in seconds

session_start();

include 'db_connection.php';
include 'sidebar.php';

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Build query with optional category filter
$query = "SELECT * FROM products WHERE name LIKE ?";
if (!empty($category_filter)) {
    $query .= " AND categories LIKE ?";
}
$query .= " ORDER BY created_at ASC";

$product_query = $conn->prepare($query);
$search_term = "%$search%";
if (!empty($category_filter)) {
    $category_term = "%$category_filter%";
    $product_query->bind_param("ss", $search_term, $category_term);
} else {
    $product_query->bind_param("s", $search_term);
}

$product_query->execute();
$result = $product_query->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Fetch distinct categories for filter dropdown
$category_query = $conn->prepare("SELECT DISTINCT categories FROM products");
$category_query->execute();
$category_result = $category_query->get_result();
$categories = $category_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Products | MarkeTrack</title>
<link href="css/style.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
<style>
    .variation-row { display: none; background: #f8f9fc; }
    .show-variation { cursor: pointer; color: #4e73df; text-decoration: underline; }
    ul.variation-list { list-style: none; padding-left: 0; margin-bottom: 0; }
    ul.variation-list li { padding: 5px 0; border-bottom: 1px solid #ddd; }
    ul.variation-list li:last-child { border-bottom: none; }
    .img-placeholder { width: 80px; height: 60px; background-color: #ddd; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #888; font-size: 14px; }
</style>
</head>
<body>
<div class="container-fluid mt-4">
    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <form action="" method="get" class="form-inline">
            <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Search Products" value="<?= htmlspecialchars($search) ?>" />
            <select name="category" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['categories']) ?>" <?= $category['categories'] === $category_filter ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['categories']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        
        </form>
        <?php if ($role === 'Brand Manager'): ?>
    <a href="add_product.php" class="btn btn-success btn-sm ml-2" title="Click to add a new product">➕ Add New Product</a>
<?php endif; ?>

    </div>
</div>

        </div>
        <div class="card-body">
            <?php if (count($products) > 0): ?>
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Categories</th>
                                <?php if ($role === 'Brand Manager'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                                <th>Variations</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td>
                                    <?php
                                    $image_folder = 'img/';
                                    $image_name = $row['image'];
                                    $image_path = $image_folder . $image_name;
                                    if (!empty($image_name) && file_exists($image_path)) {
                                        echo "<img src=\"$image_path\" alt=\"" . htmlspecialchars($row['name']) . "\" style=\"width:70px;height:50px;object-fit:cover;border-radius:5px;\">";
                                    } else {
                                        echo "<div class=\"img-placeholder\">No Image</div>";
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['categories']) ?></td>
                                <?php if ($role === 'Brand Manager'): ?>
                                <td>
                                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mr-1" title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="btn btn-danger btn-sm" title="Delete Product">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                <?php endif; ?>
                                <td>
                                    <span class="show-variation" data-id="<?= $row['id'] ?>">Show Variations</span>
                                </td>
                            </tr>
                            <tr class="variation-row" id="variation-row-<?= $row['id'] ?>">
                                <td colspan="<?= $role === 'Brand Manager' ? 6 : 5 ?>">
                                    <?php
                                    $vid = (int)$row['id'];
                                    $variation_query = $conn->prepare("SELECT * FROM product_variations WHERE product_id = ?");
                                    $variation_query->bind_param("i", $vid);
                                    $variation_query->execute();
                                    $variation_result = $variation_query->get_result();

                                    if ($variation_result && $variation_result->num_rows > 0) {
                                        echo "<ul class='variation-list'>";
                                        while ($v = $variation_result->fetch_assoc()) {
                                            $pack_size = htmlspecialchars($v['pack_size'] ?? 'N/A');
                                            $price_unit = number_format($v['price_unit'] ?? 0, 2);
                                            $price_case = number_format($v['price_case'] ?? 0, 2);
                                           

                                            echo "<li>
                                                <b>Flavor:</b> " . htmlspecialchars($v['flavor']) . " | 
                                                <b>Size:</b> $pack_size | 
                                                <b>Price per Unit:</b> ₱$price_unit | 
                                                <b>Price per Case:</b> ₱$price_case | 
                                               
                                            </li>";
                                        }
                                        echo "</ul>";
                                    } else {
                                        echo "<span style='color:gray;'>No variations found.</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    $('.show-variation').click(function() {
        var id = $(this).data('id');
        var row = $('#variation-row-' + id);
        if (row.is(':visible')) {
            row.hide();
            $(this).text('Show Variations');
        } else {
            row.show();
            $(this).text('Hide Variations');
        }
    });
});
</script>
</body>
</html>
