<?php
session_start();


$adminUser = "admin";
$adminPass = "password123";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";

    if ($username === $adminUser && $password === $adminPass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-review.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="assets/styles.css" />
  <style>
    body {
      background: #004aad;
      color: white;
      font-family: Arial, sans-serif;
      margin: 0; padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background: white;
      color: #004aad;
      padding: 2rem 3rem;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 74, 173, 0.5);
      width: 350px;
      text-align: center;
    }
    h1 {
      margin-bottom: 1.5rem;
    }
    label {
      display: block;
      text-align: left;
      margin-bottom: 0.3rem;
      font-weight: bold;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.6rem;
      margin-bottom: 1.2rem;
      border: 2px solid #004aad;
      border-radius: 8px;
      font-size: 1rem;
    }
    button {
      background-color: #004aad;
      color: white;
      border: none;
      padding: 0.75rem 0;
      width: 100%;
      border-radius: 8px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #00337a;
    }
    .error {
      background: #ffcccc;
      color: #a00;
      padding: 0.7rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>Admin Login</h1>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="admin-login.php">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required />

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
