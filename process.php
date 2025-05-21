<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit;
}

$file = 'data/applications.json';
$applications = [];

if (file_exists($file)) {
    $applications = json_decode(file_get_contents($file), true);
} else {
    die("Applications file not found.");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Application ID missing.");
}


$app = null;
foreach ($applications as $index => $a) {
    if ($a['id'] === $id) {
        $app = $a;
        $appIndex = $index;
        break;
    }
}

if (!$app) {
    die("Application not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    if (in_array($status, ['Approved', 'Rejected'])) {
        $applications[$appIndex]['status'] = $status;
        file_put_contents($file, json_encode($applications, JSON_PRETTY_PRINT));
        header("Location: admin-review.php?message=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Review Application</title>
  <link rel="stylesheet" href="assets/styles.css" />
  <style>
    .container {
      max-width: 700px;
      margin: 3rem auto;
      background: #eef4ff;
      padding: 2rem;
      border-radius: 12px;
      color: #004aad;
      font-family: Arial, sans-serif;
    }
    h1 {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .field {
      margin-bottom: 1rem;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 0.3rem;
    }
    a.file-link {
      display: inline-block;
      margin-top: 0.3rem;
      color: #004aad;
      text-decoration: underline;
      cursor: pointer;
    }
    a.file-link:hover {
      color: #002f6c;
    }
    .actions {
      text-align: center;
      margin-top: 2rem;
    }
    button {
      background-color: #004aad;
      color: white;
      border: none;
      padding: 0.7rem 1.5rem;
      margin: 0 1rem;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #00337a;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Review Application</h1>

    <div class="field">
      <label>Full Name:</label>
      <div><?= htmlspecialchars($app['fullname']) ?></div>
    </div>

    <div class="field">
      <label>Email:</label>
      <div><?= htmlspecialchars($app['email']) ?></div>
    </div>

    <div class="field">
      <label>Program:</label>
      <div><?= htmlspecialchars($app['program']) ?></div>
    </div>

    <div class="field">
      <label>Status:</label>
      <div><?= htmlspecialchars($app['status']) ?></div>
    </div>

    <div class="field">
      <label>Birth Certificate:</label>
      <?php if (!empty($app['birth_cert']) && file_exists($app['birth_cert'])): ?>
        <a href="<?= htmlspecialchars($app['birth_cert']) ?>" target="_blank" class="file-link">View Birth Certificate</a>
      <?php else: ?>
        <div>No birth certificate uploaded</div>
      <?php endif; ?>
    </div>

    <div class="field">
      <label>Report Card:</label>
      <?php if (!empty($app['report_card']) && file_exists($app['report_card'])): ?>
        <a href="<?= htmlspecialchars($app['report_card']) ?>" target="_blank" class="file-link">View Report Card</a>
      <?php else: ?>
        <div>No report card uploaded</div>
      <?php endif; ?>
    </div>

    <form method="POST" class="actions">
      <button type="submit" name="status" value="Approved">Approve</button>
      <button type="submit" name="status" value="Rejected">Reject</button>
    </form>
  </div>
</body>
</html>
