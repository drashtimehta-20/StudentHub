<?php
require_once 'auth.php';
require_login($conn, 'admin');

$users = $conn->query("SELECT user_id, username, email, role, last_login FROM users ORDER BY user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding:20px; }
        table { border-collapse:collapse; width:100%; margin-top:15px; }
        th, td { border:1px solid #999; padding:8px; text-align:left; }
        th { background:#2c3e50; color:#fff; }
    </style>
</head>
<body>
<h2>Admin Dashboard</h2>
<p>Welcome, <b><?= e($_SESSION['username']) ?></b> (role: <?= e($_SESSION['role']) ?>)</p>
<p>Last login: <?= e($_SESSION['prev_login'] ?? 'This is your first login') ?></p>
<?= logout_button() ?>

<h3>All Registered Users</h3>
<table>
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Last Login</th></tr>
    <?php while ($r = $users->fetch_assoc()): ?>
        <tr>
            <td><?= e($r['user_id']) ?></td><td><?= e($r['username']) ?></td>
            <td><?= e($r['email']) ?></td><td><?= e($r['role']) ?></td>
            <td><?= e($r['last_login'] ?? '-') ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>