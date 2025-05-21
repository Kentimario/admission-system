<?php

$id = $_GET['id'] ?? '';
$file = 'data/applications.json';
$apps = [];
$found = false;

if ($id && file_exists($file)) {
    $apps = json_decode(file_get_contents($file), true);
    foreach ($apps as &$a) {
        if ($a['id'] === $id) {
            $a['status'] = 'Enrolled';
            $found = true;
            break;
        }
    }
    if ($found) {
        file_put_contents($file, json_encode($apps, JSON_PRETTY_PRINT));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Confirmation</title>
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
  <div class="container">
    <h1>Payment Confirmation</h1>
    <?php if ($found): ?>
      <p>Payment confirmed successfully!</p>
      <p>The registrar has been notified and your enrollment is complete.</p>
      <p><a href="index.html">Back to Application Form</a></p>
    <?php else: ?>
      <p>Application not found or invalid.</p>
      <p><a href="index.html">Back to Application Form</a></p>
    <?php endif; ?>
  </div>
</body>
</html>
