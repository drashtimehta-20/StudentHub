<?php
require_once 'auth.php';

// Already logged in? Go straight to the right dashboard.
if (isset($_SESSION['user_id'])) {
    header('Location: ' . dashboard_for($_SESSION['role']));
    exit;
}

$error = "";
$info  = "";
$login = "";

if (isset($_GET['timeout']))   $info = "Your session expired due to inactivity. Please log in again.";
if (isset($_GET['denied']))    $info = "Please log in to access that page.";
if (isset($_GET['loggedout'])) $info = "You have been logged out successfully.";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die("Invalid form submission (CSRF check failed).");
    }

    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = "Please enter your username/email and password.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, password_hash, role, last_login
                                FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password_hash'])) {

            // Upgrade the hash if PHP's default algorithm has improved
            if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                $new = password_hash($password, PASSWORD_DEFAULT);
                $u = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
                $u->bind_param("si", $new, $user['user_id']);
                $u->execute();
                $u->close();
            }

            login_user($conn, $user);
            log_event($conn, (int)$user['user_id'], 'LOGIN_SUCCESS');

            if (!empty($_POST['remember'])) {
                set_remember_cookie($conn, (int)$user['user_id']);
            }

            header('Location: ' . dashboard_for($user['role']));   // role-based redirect
            exit;
        }

        // Same message for "no such user" and "wrong password" so attackers learn nothing
        log_event($conn, $user ? (int)$user['user_id'] : null, 'LOGIN_FAILED');
        $error = "Invalid username/email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudentHub - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f9; margin:0; padding:20px; }
        .box { max-width:400px; margin:40px auto; background:#fff; padding:25px; border-radius:8px;
               box-shadow:0 2px 8px rgba(0,0,0,.1); }
        label { display:block; margin-top:14px; font-weight:bold; }
        input[type=text], input[type=password] { width:100%; padding:8px; margin-top:5px; box-sizing:border-box; }
        .fail { background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; }
        .info { background:#fff3cd; color:#856404; padding:10px; border-radius:4px; }
        button { margin-top:18px; padding:10px 20px; background:#2c3e50; color:#fff; border:0; cursor:pointer; }
    </style>
</head>
<body>
<div class="box">
    <h2>StudentHub Login</h2>
    <?php if ($info):  ?><p class="info"><?= e($info) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="fail"><?= e($error) ?></p><?php endif; ?>

    <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <label for="login">Username or Email</label>
        <input type="text" id="login" name="login" value="<?= e($login) ?>" autofocus>

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <label><input type="checkbox" name="remember" value="1"> Remember me for <?= REMEMBER_DAYS ?> days</label>

        <button type="submit">Login</button>
    </form>
    <p><a href="register_user.php">Create an account</a></p>
</div>
</body>
</html>