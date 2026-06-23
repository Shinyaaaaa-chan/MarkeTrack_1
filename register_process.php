<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from form
    $fullname = $_POST['fullname']; // full name input
    $username = $_POST['username']; // you should add this input in your form if not already
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact_number = $_POST['contact_number'];
    $store_name = $_POST['store_name'];
    $address = $_POST['address'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into DB
    $stmt = $conn->prepare("INSERT INTO customers (fullname, username, password, contact_number, store_name, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullname, $username, $hashed_password, $contact_number, $store_name, $address);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
