<?php
session_start();
include 'db_connection.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user information from the database
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result); // Fetch user data

// Handle the update profile form submission (if any)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $store_name = $_POST['store_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];

    // Update the user information in the database
    $update_query = "UPDATE users SET name = '$name', store_name = '$store_name', address = '$address', contact_number = '$contact_number' WHERE user_id = '$user_id'";
    mysqli_query($conn, $update_query);
    
    // Reload the page to show updated information
    header('Location: profile.php');
    exit();
}
?>

<!-- Profile page content -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h2>Profile Information</h2>
    <form method="POST" action="profile.php">
        <div>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required>
        </div>
        <div>
            <label for="store_name">Store Name:</label>
            <input type="text" id="store_name" name="store_name" value="<?= $user['store_name'] ?>" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?= $user['address'] ?>" required>
        </div>
        <div>
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" value="<?= $user['contact_number'] ?>" required>
        </div>
        <div>
            <button type="submit">Update Profile</button>
        </div>
    </form>

    <!-- Logout Button -->
    <a href="logout.php">Logout</a>
</body>
</html>
