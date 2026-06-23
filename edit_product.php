<?php
session_start();




include 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Brand Manager') {
    header("Location: products.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Fetch product info
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows === 0) {
    $stmt->close();
    header("Location: products.php");
    exit();
}

$product = $product_result->fetch_assoc();
$stmt->close();

// Fetch variations
$var_stmt = $conn->prepare("SELECT * FROM product_variations WHERE product_id = ?");
$var_stmt->bind_param("i", $product_id);
$var_stmt->execute();
$variations_result = $var_stmt->get_result();
$variations = [];
while ($row = $variations_result->fetch_assoc()) {
    $variations[] = $row;
}
$var_stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $categories = trim($_POST['categories']);
    $image = $product['image'];

    // Image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = basename($_FILES['image']['name']);
        $tmp_name = $_FILES['image']['tmp_name'];
        $target_dir = "img/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . $img_name;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $image = $img_name;
        }
    }

    // Update product (no expiration here)
    $update_stmt = $conn->prepare("UPDATE products SET name = ?, categories = ?,image = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $name, $categories, $image, $product_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Update existing variations
$existing_ids = $_POST['variation_id'] ?? [];
$existing_flavors = $_POST['flavor'] ?? [];
$existing_pack_sizes = $_POST['pack_size'] ?? [];
$existing_price_units = $_POST['price_unit'] ?? [];
$existing_price_case = $_POST['price_case'] ?? [];
$existing_stocks = $_POST['stock_var'] ?? [];
$existing_expirations = $_POST['product_expiration_var'] ?? [];
$delete_flags = $_POST['existing_variation_delete'] ?? [];

// Initialize the update statement before the loop
$update_var_stmt = $conn->prepare("UPDATE product_variations SET flavor = ?, pack_size = ?, price_unit = ?, price_case = ?, stock = ?, product_expiration = ? WHERE id = ?");
$delete_var_stmt = $conn->prepare("DELETE FROM product_variations WHERE id = ?");

for ($i = 0; $i < count($existing_ids); $i++) {
    $var_id = intval($existing_ids[$i]);
    if (in_array($var_id, $delete_flags)) {
        $delete_var_stmt->bind_param("i", $var_id);
        $delete_var_stmt->execute();
        continue;
    }

    $flavor = trim($existing_flavors[$i]);
    $pack_size = trim($existing_pack_sizes[$i]);
    $price_unit = floatval($existing_price_units[$i]);
    $price_case = floatval($existing_price_case[$i]);
    $stock_var = intval($existing_stocks[$i]);
    $expiration_var = $existing_expirations[$i];

    if ($flavor !== "" && $pack_size !== "") {
        $update_var_stmt->bind_param("ssddisi", $flavor, $pack_size, $price_unit, $price_case, $stock_var, $expiration_var, $var_id);
        $update_var_stmt->execute();
    }
}
$update_var_stmt->close();
$delete_var_stmt->close();

    // Insert new variations
   // Insert new variations
if (!empty($_POST['new_variation_flavor'])) {
    $new_flavors = $_POST['new_variation_flavor'];
    $new_pack_sizes = $_POST['new_variation_pack_size'];
    $new_price_units = $_POST['new_variation_price_unit'];
    $new_price_case = $_POST['new_variation_price_case'];
    $new_stocks = $_POST['new_variation_stock'];
    $new_expirations = $_POST['new_variation_expiration'];

    $insert_var_stmt = $conn->prepare("INSERT INTO product_variations (product_id, flavor, pack_size, price_unit, price_case, stock, product_expiration) VALUES (?, ?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < count($new_flavors); $i++) {
        $flavor = trim($new_flavors[$i]);
        $pack_size = trim($new_pack_sizes[$i]);
        $price_unit = floatval($new_price_units[$i]);
        $price_case = floatval($new_price_case[$i]);
        $stock_var = intval($new_stocks[$i]);
        $expiration_var = $new_expirations[$i];

        if ($flavor !== "" && $pack_size !== "") {
            $insert_var_stmt->bind_param("issddis", $product_id, $flavor, $pack_size, $price_unit, $price_case, $stock_var, $expiration_var);
            $insert_var_stmt->execute();
        }
    }
    $insert_var_stmt->close();
}
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Product - <?= htmlspecialchars($product['name']) ?></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
<style>
    .variation-row { margin-bottom: 10px; padding: 10px; background: #f8f9fc; border-radius: 5px; position: relative; }
    .remove-variation { cursor: pointer; color: red; font-weight: bold; position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 1.2rem; }
</style>
</head>
<body>
<div class="container mt-4">
    <h2>Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required />
        </div>
        <div class="form-group">
        <label>Category</label>
    <select name="categories" class="form-control" required>
        <option value="">Select Category</option>
        <option value="Biscuit">Biscuit</option>
        <option value="Beverages">Beverages</option>
        <option value="Candy">Candy</option>
        <option value="Wafer and Chocolate">Wafer and Chocolate</option>
        <option value="Instant Food">Instant Food</option>
        <option value="Coffee">Coffee</option>
        <option value="Cereal">Cereal</option>
        <option value="Home Care">Home Care</option>
    </select>
        </div>
        <div class="form-group">
            <label>Current Image</label><br />
            <?php
            $image_path = "img/" . $product['image'];
            if (!empty($product['image']) && file_exists($image_path)) {
                echo "<img src=\"$image_path\" alt=\"Product Image\" style=\"width:100px;height:70px;object-fit:cover;border-radius:5px;\" />";
            } else {
                echo "No image uploaded.";
            }
            ?>
        </div>
        <div class="form-group">
            <label>Change Image</label>
            <input type="file" name="image" accept="image/*" class="form-control-file" />
        </div>

        <h4>Existing Variations</h4>
        <div id="existing-variations">
            <?php foreach ($variations as $v): ?>
                <div class="variation-row">
                    <input type="hidden" name="variation_id[]" value="<?= $v['id'] ?>" />
                    <div class="form-row">
                        <div class="col-md-2">
                            <input type="text" name="flavor[]" class="form-control" placeholder="Flavor" value="<?= htmlspecialchars($v['flavor']) ?>" required />
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="pack_size[]" class="form-control" placeholder="Pack Size" value="<?= htmlspecialchars($v['pack_size']) ?>" required />
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="price_unit[]" class="form-control" placeholder="Price/Unit" value="<?= htmlspecialchars($v['price_unit']) ?>" required />
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="price_case[]" class="form-control" placeholder="Price/Case" value="<?= htmlspecialchars($v['price_case']) ?>" required />
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="stock_var[]" class="form-control" placeholder="Stock" value="<?= htmlspecialchars($v['stock']) ?>" required />
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="product_expiration_var[]" class="form-control" value="<?= htmlspecialchars($v['product_expiration']) ?>" />
                        </div>
                        <div class="col-md-1">
                            <label class="delete-checkbox" title="Delete Variation">
                                <input type="checkbox" name="existing_variation_delete[]" value="<?= $v['id'] ?>" />
                                🗑
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h4>Add New Variations</h4>
        <div id="new-variation-container">
            <div class="variation-row row">
                <div class="col-md-2">
                    <input type="text" name="new_variation_flavor[]" class="form-control" placeholder="Flavor" />
                </div>
                <div class="col-md-2">
                    <input type="text" name="new_variation_pack_size[]" class="form-control" placeholder="Pack Size" />
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="new_variation_price_unit[]" class="form-control" placeholder="Price/Unit" />
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="new_variation_price_case[]" class="form-control" placeholder="Price/Case" />
                </div>
                <div class="col-md-2">
                    <input type="number" name="new_variation_stock[]" class="form-control" placeholder="Stock" />
                </div>
                <div class="col-md-3">
                    <input type="date" name="new_variation_expiration[]" class="form-control" />
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-variation" onclick="this.closest('.variation-row').remove()">×</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addNewVariationRow()">Add Variation</button>

        <br />
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
function addVariationRow() {
    const container = document.getElementById('variation-container');
    const newRow = document.createElement('div');
    newRow.className = 'variation-row row';

    newRow.innerHTML = `
        <div class="col-md-2">
            <label class="variation-label">Flavor</label>
            <input type="text" name="new_variation_flavor[]" class="form-control" placeholder="e.g. Chocolate" required />
        </div>
        <div class="col-md-2">
            <label class="variation-label">Pack Size</label>
            <input type="text" name="new_variation_pack_size[]" class="form-control" placeholder="e.g. 40g Sachet" required />
        </div>
        <div class="col-md-2">
            <label class="variation-label">Price/Unit</label>
            <input type="number" step="0.01" name="new_variation_price_unit[]" class="form-control" placeholder="₱0.00" required />
        </div>
        <div class="col-md-2">
            <label class="variation-label">Price/Case</label>
            <input type="number" step="0.01" name="new_variation_price_case[]" class="form-control" placeholder="₱0.00" required />
        </div>
        <div class="col-md-2">
            <label class="variation-label">Stock</label>
            <input type="number" name="new_variation_stock[]" class="form-control" placeholder="Qty" required />
        </div>
        <div class="col-md-2">
            <label class="variation-label">Expiration</label>
            <input type="date" name="new_variation_expiration[]" class="form-control" required />
        </div>
        <div class="col-md-12 text-right mt-2">
            <button type="button" class="btn btn-outline-danger btn-sm remove-variation" onclick="this.closest('.variation-row').remove()">Remove</button>
        </div>
    `;

    container.appendChild(newRow);
}

</script>
</body>
</html>
