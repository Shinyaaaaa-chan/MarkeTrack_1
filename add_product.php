<?php
ini_set('session.gc_maxlifetime', 3600); // 1 hour in seconds
session_set_cookie_params(3600);  
session_start();

include 'db_connection.php';

// Only allow Brand Manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Brand Manager') {
    header("Location: products.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $categories = trim($_POST['categories']);
    $image = '';

    // Handle image upload
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

    // Insert product (without expiration)
    $stmt = $conn->prepare("INSERT INTO products (name, categories, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $categories, $image);
    $stmt->execute();
    $product_id = $stmt->insert_id;
    $stmt->close();

    // Insert variations
    if (!empty($_POST['new_variation_flavor'])) {
        $flavors = $_POST['new_variation_flavor'];
        $pack_sizes = $_POST['new_variation_pack_size'];
        $price_units = $_POST['new_variation_price_unit'];
        $price_case = $_POST['new_variation_price_case'];
        $stocks = $_POST['new_variation_stock'];
        $expirations = $_POST['new_variation_expiration'];

        $var_stmt = $conn->prepare("INSERT INTO product_variations (product_id, flavor, pack_size, price_unit, price_case, stock, product_expiration) VALUES (?, ?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($flavors); $i++) {
            $flavor = trim($flavors[$i]);
            $pack_size = trim($pack_sizes[$i]);
            $price_unit = floatval($price_units[$i]);
            $price_case = floatval($price_case[$i]);
            $stock = intval($stocks[$i]);
            $expiration = $expirations[$i];

            if ($flavor !== "" && $pack_size !== "") {
                $var_stmt->bind_param("issddis", $product_id, $flavor, $pack_size, $price_unit, $price_case, $stock, $expiration);
                $var_stmt->execute();
            }
        }
        $var_stmt->close();
    }

    header("Location: products.php");
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Product</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
<style>
    .variation-row {
        background: #ffffff;
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .remove-variation {
        margin-top: 30px;
    }

    .variation-label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .add-btn {
        background-color: #4e73df;
        color: white;
    }

    .add-btn:hover {
        background-color: #2e59d9;
        color: white;
    }
</style>
</head>
<body>
<div class="container mt-4">
    <h2>Add New Product</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required />
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
    
    </select>
</div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" class="form-control-file" />
        </div>

        <h4>Variations</h4>
        <div id="variation-container">
        <div class="variation-row row">
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
</div>

            </div>
        
        <button type="button" class="btn btn-secondary mb-3" onclick="addVariationRow()">Add Variation</button>
        <br />
        <button type="submit" class="btn btn-primary">Save Product</button>
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
