<?php
session_start();
include 'db_connection.php';  // Database connection included

// Your existing PHP code here...
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | MarkeTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #5a8dee, #2c60c6);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: auto;  /* ✅ Para di lumagpas ang content */
        }

        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 650px;
            width: 100%;
            transition: 0.3s;
            margin-top: 40px; /* ✅ Para makita ang header agad */
        }

        .container:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
        }

        .btn-primary {
            background: #4c78e0;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #3b63c4;
        }

        a {
            text-decoration: none;
            color: #4c78e0;
        }

        a:hover {
            color: #3b63c4;
        }
    </style>
</head>

<body>

<div class="container">
    <h2 class="text-center mb-4">Create an Account!</h2>  <!-- ✅ Siguradong kita na agad ito -->
    
    <form action="register_process.php" method="POST">
        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
        </div>

         <!-- Name -->
         <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your Username" required>
        </div>

        <!-- Address -->
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" id="address" name="address" class="form-control" placeholder="Enter your address" required>
        </div>

        <!-- Store Name -->
        <div class="mb-3">
            <label for="store_name" class="form-label">Store Name</label>
            <input type="text" id="store_name" name="store_name" class="form-control" placeholder="Enter your store name" required>
        </div>

        <!-- Contact Number -->
        <div class="mb-3">
            <label for="contact" class="form-label">Contact Number</label>
            <input type="tel" id="contact" name="contact" class="form-control" placeholder="Enter your contact number" pattern="[0-9]{10,12}" required>
        </div>

        <!-- Password -->
        <div class="row g-3">
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-md-6">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Repeat Password" required>
            </div>
        </div>

        <!-- Register Button -->
        <div class="mt-4 d-grid">
            <button type="submit" class="btn btn-primary">Register Account</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="forgot-password.php">Forgot Password?</a>
    </div>
    <div class="text-center mt-2">
        <a href="login.php">Already have an account? Login!</a>
    </div>
</div>

</body>
</html>
