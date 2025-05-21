<?php

$id = $_GET['id'] ?? '';
$file = 'data/applications.json';
$app = null;

if ($id && file_exists($file)) {
    $apps = json_decode(file_get_contents($file), true);
    foreach ($apps as $a) {
        if ($a['id'] === $id) {
            $app = $a;
            break;
        }
    }
}

if (!$app) {
    die('Application not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Offer Letter</title>
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
  <div class="container">
    <h1>Offer Letter</h1>
    <?php if ($app['status'] === 'Approved'): ?>
      <p>Dear <?= htmlspecialchars($app['fullname']) ?>,</p>
      <p>Congratulations! You have been accepted into the <strong><?= htmlspecialchars($app['program']) ?></strong> program.</p>
      <p>Please proceed with payment to confirm your enrollment.</p>
      <p><a href="confirm.php?id=<?= urlencode($app['id']) ?>"><button>Confirm Payment</button></a></p>
    <?php elseif ($app['status'] === 'Rejected'): ?>
      <p>Dear <?= htmlspecialchars($app['fullname']) ?>,</p>
      <p>We regret to inform you that your application has been <strong>rejected</strong>.</p>
      <p>You may resubmit your application with the required documents.</p>
      <p><a href="index.html"><button>Resubmit Application</button></a></p>
    <?php else: ?>
      <p>Your application is still under review. Please wait for further updates.</p>
    <?php endif; ?>
  </div>
</body>
</html>
