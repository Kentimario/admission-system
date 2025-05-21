<?php

session_start();

$file = 'data/applications.json';
$applications = [];

if (file_exists($file)) {
    $applications = json_decode(file_get_contents($file), true);
} else {
    die("Applications file not found.");
}

$email = $_GET['email'] ?? ($_POST['email'] ?? '');

$app = null;
$appIndex = null;

if ($email) {
    foreach ($applications as $index => $a) {
        if (strtolower($a['email']) === strtolower($email)) {
            $app = $a;
            $appIndex = $index;
            break;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now']) && $app) {
    $applications[$appIndex]['payment_status'] = 'Paid';
    file_put_contents($file, json_encode($applications, JSON_PRETTY_PRINT));
    $app = $applications[$appIndex];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Check Application Status</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef4ff;
      color: #004aad;
      padding: 30px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 25px 35px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      text-align: center;
    }
    .status {
      font-weight: bold;
      font-size: 1.2rem;
      margin-top: 15px;
      margin-bottom: 15px;
    }
    .message {
      margin-top: 20px;
      background: #d6eaff;
      border: 1px solid #004aad;
      padding: 15px;
      border-radius: 8px;
      font-weight: bold;
    }
    .redirect-message {
      margin-top: 20px;
      font-weight: bold;
      color: red;
    }
    a.back-link {
      display: inline-block;
      margin-top: 25px;
      color: #004aad;
      text-decoration: underline;
      font-weight: bold;
    }
    a.back-link:hover {
      color: #002f6c;
    }
    button {
      background: #004aad;
      color: white;
      border: none;
      padding: 0.7rem 1.5rem;
      border-radius: 8px;
      font-size: 1.1rem;
      margin-top: 1.5rem;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background: #00337a;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Application Status Check</h1>

    <?php if (!$email): ?>
      <div class="message">Please enter your email to check application status.</div>
      <a href="index.html" class="back-link">Back to Application Form</a>

    <?php elseif (!$app): ?>
      <div class="message">No application found for <strong><?= htmlspecialchars($email) ?></strong>.</div>
      <a href="index.html" class="back-link">Back to Application Form</a>

    <?php else: ?>
      <div><strong>Full Name:</strong> <?= htmlspecialchars($app['fullname']) ?></div>
      <div><strong>Email:</strong> <?= htmlspecialchars($app['email']) ?></div>
      <div class="status">Status: <?= htmlspecialchars($app['status']) ?></div>

      <?php if (!empty($app['message'])): ?>
        <div class="message"><?= nl2br(htmlspecialchars($app['message'])) ?></div>
      <?php else: ?>
        <div class="message">No additional message from the admin yet.</div>
      <?php endif; ?>

      <?php if ($app['status'] === 'Rejected'): ?>
        <div class="redirect-message">
          You will be redirected to the application form to resubmit your documents.
        </div>
        <script>
          setTimeout(() => {
            window.location.href = 'index.html';
          }, 10000); 
        </script>
      <?php endif; ?>

      <?php if ($app['status'] === 'Approved'): ?>
        <?php if (!isset($app['payment_status']) || $app['payment_status'] !== 'Paid'): ?>
          <form method="GET" action="make-payment.php" style="margin-top: 20px;">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <button type="submit">Proceed to Payment</button>
          </form>
        <?php else: ?>
          <div class="message" style="color:green;">
            Enrollment complete! Thank you for your payment.
          </div>
        

        <?php endif; ?>
      <?php endif; ?>

      <a href="index.html" class="back-link">Back to Application Form</a>
    <?php endif; ?>
  </div>
</body>
</html>
-
