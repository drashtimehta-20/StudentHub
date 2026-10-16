<?php
session_name('sh_auth');
session_start();
require_once 'db_mysqli.php';

// ---------- CSRF token (Advanced extension) ----------
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ---------- Helpers ----------
function clean($v) { return trim(strip_tags($v)); }
function e($v)     { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// Audit log writer (Advanced extension)
function audit($conn, $userId, $action) {
    $ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $log = $conn->prepare("INSERT INTO audit_log (user_id, action, ip_address) VALUES (?, ?, ?)");
    $log->bind_param("iss", $userId, $action, $ip);
    $log->execute();
    $log->close();
}

$errors  = [];
$success = "";
$old     = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. CSRF check
    if (!isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die("Invalid form submission (CSRF check failed).");
    }

    // 2. Sanitize (passwords are NOT sanitized, because changing them would alter what the user typed)
    $old['username'] = clean($_POST['username'] ?? '');
    $old['email']    = clean($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirm         = $_POST['confirm_password'] ?? '';

    // 3. Validate (same rules as the JavaScript below)
    if ($old['username'] === '') {
        $errors['username'] = "Username is required.";
    } elseif (!preg_match('/^[A-Za-z0-9_]{4,20}$/', $old['username'])) {
        $errors['username'] = "Username must be 4-20 characters: letters, numbers, underscore.";
    }

    if ($old['email'] === '') {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email address.";
    }

    if ($password === '') {
        $errors['password'] = "Password is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,64}$/', $password)) {
        $errors['password'] = "Password needs 8+ characters with uppercase, lowercase, number and symbol.";
    }

    if ($confirm !== $password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // 4. Duplicate check using a prepared statement (Intermediate extension)
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $old['username'], $old['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if (strcasecmp($row['username'], $old['username']) === 0) {
                $errors['username'] = "This username is already taken.";
            }
            if (strcasecmp($row['email'], $old['email']) === 0) {
                $errors['email'] = "This email is already registered.";
            }
        }
        $stmt->close();

        if (!empty($errors)) {
            audit($conn, null, "REGISTER_DUPLICATE");
        }
    }

    // 5. Hash the password and insert
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn->begin_transaction();

            $ins = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $ins->bind_param("sss", $old['username'], $old['email'], $hash);
            $ins->execute();
            $newId = $conn->insert_id;
            $ins->close();

            audit($conn, $newId, "REGISTER_SUCCESS");
            $conn->commit();

            $success = "Account created successfully! Welcome, " . e($old['username']) . ".";
            $old = ['username' => '', 'email' => ''];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));   // fresh token

        } catch (mysqli_sql_exception $ex) {
            $conn->rollback();
            if ($ex->getCode() == 1062) {          // duplicate caught by the UNIQUE key
                $errors['email'] = "This username or email is already registered.";
            } else {
                error_log($ex->getMessage());
                $errors['general'] = "Something went wrong. Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudentHub - Create Account</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f9; margin:0; padding:20px; }
        .box { max-width:460px; margin:auto; background:#fff; padding:25px; border-radius:8px;
               box-shadow:0 2px 8px rgba(0,0,0,.1); }
        label { display:block; margin-top:14px; font-weight:bold; }
        input { width:100%; padding:8px; margin-top:5px; box-sizing:border-box; }
        .error { color:#c0392b; font-size:14px; }
        .success { background:#d4edda; color:#155724; padding:10px; border-radius:4px; }
        .fail { background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; }
        button { margin-top:18px; padding:10px 20px; background:#2c3e50; color:#fff; border:0; cursor:pointer; }
    </style>
</head>
<body>
<div class="box">
    <h2>Create Account</h2>

    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
    <?php if (!empty($errors)): ?><p class="fail">Please fix the errors below.</p><?php endif; ?>
    <?php if (isset($errors['general'])): ?><p class="error"><?= e($errors['general']) ?></p><?php endif; ?>

    <form id="regForm" method="POST" action="register_user.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= e($old['username']) ?>">
        <span class="error" id="err-username"><?= e($errors['username'] ?? '') ?></span>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= e($old['email']) ?>">
        <span class="error" id="err-email"><?= e($errors['email'] ?? '') ?></span>

        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <span class="error" id="err-password"><?= e($errors['password'] ?? '') ?></span>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <span class="error" id="err-confirm_password"><?= e($errors['confirm_password'] ?? '') ?></span>

        <button type="submit">Register</button>
    </form>
</div>

<!-- ========== FRONTEND VALIDATION (same rules as PHP) ========== -->
<script>
document.getElementById('regForm').addEventListener('submit', function (ev) {
    const v = id => document.getElementById(id).value.trim();
    const pw  = document.getElementById('password').value;
    const cpw = document.getElementById('confirm_password').value;
    const msgs = { username:'', email:'', password:'', confirm_password:'' };

    if (!/^[A-Za-z0-9_]{4,20}$/.test(v('username')))
        msgs.username = 'Username must be 4-20 characters: letters, numbers, underscore.';
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v('email')))
        msgs.email = 'Enter a valid email address.';
    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,64}$/.test(pw))
        msgs.password = 'Password needs 8+ characters with uppercase, lowercase, number and symbol.';
    if (cpw !== pw || cpw === '')
        msgs.confirm_password = 'Passwords do not match.';

    let bad = false;
    for (const key in msgs) {
        document.getElementById('err-' + key).textContent = msgs[key];
        if (msgs[key]) bad = true;
    }
    if (bad) ev.preventDefault();      // stop submission if anything is wrong
});
</script>
</body>
</html>