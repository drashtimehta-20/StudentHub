<?php
require_once 'auth.php';
require_login($conn, 'student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>body { font-family: Arial, sans-serif; padding:20px; }</style>
</head>
<body>
<h2>Student Dashboard</h2>
<p>Welcome, <b><?= e($_SESSION['username']) ?></b> (role: <?= e($_SESSION['role']) ?>)</p>
<p>Last login: <?= e($_SESSION['prev_login'] ?? 'This is your first login') ?></p>
<p>Your session expires after <?= SESSION_TIMEOUT ?> seconds of inactivity.</p>
<?= logout_button() ?>
</body>
</html>