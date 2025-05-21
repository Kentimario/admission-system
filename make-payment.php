<?php
session_start();

$file = 'data/applications.json';
$applications = [];

if (file_exists($file)) {
    $applications = json_decode(file_get_contents($file), true);
} else {
    die("Applications file not found.");
}

$email = $_GET['email'] ?? '';
$appIndex = null;
$app = null;

if ($email) {
    foreach ($applications as $index => $a) {
        if (strtolower($a['email']) === strtolower($email)) {
            $app = $a;
            $appIndex = $index;
            break;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $app) {
    $applications[$appIndex]['payment_status'] = 'Paid';
    file_put_contents($file, json_encode($applications, JSON_PRETTY_PRINT));

    
    header("Location: check-status.php?email=" . urlencode($email) . "&payment_confirmed=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Make Payment</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef4ff;
      text-align: center;
      padding: 40px;
    }
    .box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      max-width: 500px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h1 {
      color: #004aad;
    }
    button {
      margin-top: 20px;
      background: #004aad;
      color: white;
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }
    button:hover {
      background: #00337a;
    }
  </style>
</head>
<body>
  <div class="box">
    <h1>Simulated Payment</h1>

    <?php if ($app): ?>
      <p>Applicant: <strong><?= htmlspecialchars($app['fullname']) ?></strong></p>
      <p>Email: <strong><?= htmlspecialchars($app['email']) ?></strong></p>
      <p>Amount Due: <strong>4795</strong></p>
      <form method="POST">
        <button type="submit">Confirm Payment</button>
      </form>
    <?php else: ?>
      <p>Application not found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
