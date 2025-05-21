<?php

$applicationsFile = 'data/applications.json';
$applications = [];

if (file_exists($applicationsFile)) {
    $json = file_get_contents($applicationsFile);
    $applications = json_decode($json, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Review</title>
  <link rel="stylesheet" href="assets/styles.css" />
  <style>
    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    .logout-link {
      font-weight: 600;
      color: #004aad;
      text-decoration: none;
      border: 1.5px solid #004aad;
      padding: 0.3rem 0.8rem;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }
    .logout-link:hover {
      background-color: #004aad;
      color: white;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5rem;
    }
    table th, table td {
      padding: 0.75rem;
      border: 1px solid #004aad;
      text-align: center;
    }
    table th {
      background: #004aad;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header-container">
      <h1>Admin Review Panel</h1>
      <a href="admin-logout.php" class="logout-link">Logout</a>
    </div>

    <?php if (empty($applications)): ?>
      <p class="message">No applications found.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Program</th>
            <th>Status</th>
            <th>Payment Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications as $app): ?>
            <tr>
              <td><?= htmlspecialchars($app['fullname']) ?></td>
              <td><?= htmlspecialchars($app['email']) ?></td>
              <td><?= htmlspecialchars($app['program']) ?></td>

              
              <td>
                <?php
                  $status = $app['status'] ?? 'Pending';
                  $color = match ($status) {
                    'Approved' => 'green',
                    'Rejected' => 'red',
                    'Pending' => 'orange',
                    default => 'black'
                  };
                  echo "<span style='color:$color;font-weight:bold;'>$status</span>";
                ?>
              </td>

              
              <td>
                <?php
                  $paid = isset($app['payment_status']) && $app['payment_status'] === 'Paid';
                  echo $paid
                    ? '<span style="color:green;font-weight:bold;">Paid</span>'
                    : '<span style="color:red;font-weight:bold;">Not Paid</span>';
                ?>
              </td>

              <td>
                <a href="process.php?id=<?= urlencode($app['id']) ?>">Review</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <p style="text-align:center;">
      <a href="index.html">Back to Application Form</a>
    </p>
  </div>
</body>
</html>
