<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Missing ID");
}

$file = 'data/applications.json';
$applications = [];

if (file_exists($file)) {
    $applications = json_decode(file_get_contents($file), true);
} else {
    die("Applications file not found.");
}

$found = false;
foreach ($applications as $index => $app) {
    if ($app['id'] === $id) {
        
        $applications[$index]['enrollment_status'] = 'Completed';
        file_put_contents($file, json_encode($applications, JSON_PRETTY_PRINT));
        $found = true;
        break;
    }
}

if ($found) {
    echo "Enrollment finalized for applicant ID $id. <a href='admin-review.php'>Back to Admin Review</a>";
} else {
    echo "Application not found.";
}
