<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}


$appId = uniqid();


$birthCertPath = '';
$reportCardPath = '';

if (isset($_FILES['birth_cert']) && $_FILES['birth_cert']['error'] === UPLOAD_ERR_OK) {
    $birthCertPath = $uploadDir . $appId . "_birthcert_" . basename($_FILES['birth_cert']['name']);
    move_uploaded_file($_FILES['birth_cert']['tmp_name'], $birthCertPath);
}

if (isset($_FILES['report_card']) && $_FILES['report_card']['error'] === UPLOAD_ERR_OK) {
    $reportCardPath = $uploadDir . $appId . "_reportcard_" . basename($_FILES['report_card']['name']);
    move_uploaded_file($_FILES['report_card']['tmp_name'], $reportCardPath);
}


$app = [
    'id' => $appId,
    'fullname' => isset($_POST['fullname']) ? trim($_POST['fullname']) : '',
    'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
    'program' => isset($_POST['program']) ? trim($_POST['program']) : '',
    'birth_cert' => $birthCertPath,
    'report_card' => $reportCardPath,
    'status' => 'Pending',
    'submitted_at' => date('Y-m-d H:i:s')
];


$file = 'data/applications.json';
$apps = [];

if (file_exists($file)) {
    $json = file_get_contents($file);
    $apps = json_decode($json, true);
    if (!is_array($apps)) {
        $apps = [];
    }
}


$apps[] = $app;
file_put_contents($file, json_encode($apps, JSON_PRETTY_PRINT));


echo "<h2 style='color:green; text-align:center;'>Application submitted successfully!</h2>";
echo "<p style='text-align:center;'><a href='index.html'>Submit another application</a> | <a href='admin-login.php'>Go to Admin Login</a></p>";
exit;
