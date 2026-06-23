<?php
include 'db_connection.php'; // Adjust path if needed

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $store_name = $_POST['store_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password

    $stmt = $conn->prepare("INSERT INTO customers (fullname, store_name, address, contact_number, username, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullname, $store_name, $address, $contact_number, $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Customer added successfully!'); window.location.href='customer.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <style>
        form {
            width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input[type="text"], input[type="tel"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2 style="text-align:center;">Add New Customer</h2>

    <form method="POST" action="add_customer.php">
        <label for="fullname">Full Name:</label>
        <input type="text" name="fullname" id="fullname" required>

        <label for="store_name">Store Name:</label>
        <input type="text" name="store_name" id="store_name" required>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" required>

        <label for="contact_number">Contact Number:</label>
        <input type="tel" name="contact_number" id="contact_number" required>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Add Customer">
    </form>

</body>
</html>
