<?php
session_start();
include 'db_connection.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }
    } else {
        $_SESSION['error'] = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: #f0f2f5;
    }

    .container {
      display: flex;
      width: 800px;
      height: 500px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      background: white;
    }

    .left {
      width: 50%;
      background: linear-gradient(to right, #ff3c3c, #ff7b00);
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 30px;
      border-top-left-radius: 20px;
      border-bottom-left-radius: 20px;
    }

    .left h2 {
      margin-bottom: 10px;
      font-size: 28px;
      text-align: center;
    }

    .left p {
      font-size: 16px;
      margin-bottom: 30px;
      text-align: center;
    }

    .left button {
      padding: 12px 30px;
      border: none;
      border-radius: 25px;
      background-color: white;
      color: #ff3c3c;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .left button:hover {
      background-color: #f0f0f0;
    }

    .right {
      width: 50%;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .right h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .right form {
      display: flex;
      flex-direction: column;
    }

    .right input[type="text"],
    .right input[type="password"] {
      margin-bottom: 15px;
      padding: 12px 45px 12px 12px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
      width: 100%;
    }

    .password-wrapper {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      right: 15px;
      top: 45%;
      transform: translateY(-50%);
      font-size: 14px;
      color: #888;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .right button {
      padding: 12px;
      background-color: #a60000;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .right button:hover {
      background-color: #8b0000;
    }

    .error-message {
      color: red;
      margin-bottom: 10px;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        height: auto;
        width: 90%;
      }

      .left, .right {
        width: 100%;
        border-radius: 0;
      }

      .left {
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        border-bottom-left-radius: 0;
      }

      .right {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left">
      <h2>Welcome Back to Marketrack!</h2>
      <p>Enter your personal details to use all of the features</p>
    </div>
    <div class="right">
      <h2>Login to Your Account</h2>

      <?php if (isset($_SESSION['error'])): ?>
        <p class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
      <?php endif; ?>

      <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required />

        <div class="password-wrapper">
          <input type="password" name="password" id="password" placeholder="Password" required />
          <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
        </div>

        <button type="submit" name="login">LOG IN</button>
      </form>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
      const isHidden = passwordField.getAttribute("type") === "password";
      passwordField.setAttribute("type", isHidden ? "text" : "password");

      if (isHidden) {
        this.classList.remove("fa-eye-slash");
        this.classList.add("fa-eye");
      } else {
        this.classList.remove("fa-eye");
        this.classList.add("fa-eye-slash");
      }
    });
  </script>
</body>
</html>