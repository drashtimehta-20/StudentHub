<?php
require_once 'config.php';
require_once 'functions.php';
$pageTitle = 'Login';

if (!empty($_SESSION['user_id']) && !empty($_SESSION['role'])) {
    header('Location: ' . home_for($_SESSION['role']));
    exit;
}

$error = '';
$info  = '';
$login = '';
if (isset($_GET['timeout']))   $info = 'Your session expired due to inactivity. Please log in again.';
if (isset($_GET['inactive']))  $info = 'Your account is inactive. Please contact the administrator.';
if (isset($_GET['loggedout'])) $info = 'You have been logged out successfully.';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { http_response_code(403); die('Invalid form submission (CSRF check failed).'); }

    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Please enter your username/email and password.';
    } else {
        $s = $conn->prepare("SELECT user_id, username, password_hash, role, status FROM users WHERE username = ? OR email = ?");
        $s->bind_param('ss', $login, $login);
        $s->execute();
        $user = $s->get_result()->fetch_assoc();
        $s->close();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] !== 'active') {
                audit($conn, (int)$user['user_id'], 'LOGIN_BLOCKED', 'Inactive account tried to log in');
                $error = 'Your account is inactive. Please contact the administrator.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id']       = (int)$user['user_id'];
                $_SESSION['username']      = $user['username'];
                $_SESSION['role']          = $user['role'];
                $_SESSION['last_activity'] = time();
                $_SESSION['csrf']          = bin2hex(random_bytes(32));

                $u = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $u->bind_param('i', $_SESSION['user_id']);
                $u->execute();
                $u->close();

                audit($conn, (int)$user['user_id'], 'LOGIN_SUCCESS', 'Logged in as ' . $user['role']);
                header('Location: ' . home_for($user['role']));
                exit;
            }
        } else {
            audit($conn, $user ? (int)$user['user_id'] : null, 'LOGIN_FAILED', 'Attempt with: ' . $login);
            $error = 'Invalid username/email or password.';
        }
    }
}

require 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm"><div class="card-body">
      <h4 class="mb-3">Login</h4>
      <?php if ($info):  ?><div class="alert alert-warning"><?= e($info) ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
      <form method="POST" action="login.php">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <label class="form-label" for="login">Username or Email</label>
        <input class="form-control mb-3" type="text" id="login" name="login" value="<?= e($login) ?>" autofocus>
        <label class="form-label" for="password">Password</label>
        <input class="form-control mb-3" type="password" id="password" name="password">
        <button class="btn btn-dark w-100">Login</button>
      </form>
    </div></div>
  </div>
</div>
<?php require 'footer.php'; ?>