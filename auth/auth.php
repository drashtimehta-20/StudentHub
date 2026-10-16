<?php
require_once __DIR__ . '/db_mysqli.php';

const SESSION_TIMEOUT = 300;    
const REMEMBER_DAYS   = 30;

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,          // cookie dies when browser closes
        'path'     => '/',
        'httponly' => true,       // JavaScript cannot read the cookie
        'samesite' => 'Lax',      // helps against CSRF
    ]);
    session_name('sh_auth');
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// ---------- Audit log ----------
function log_event($conn, $userId, $action) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $s = $conn->prepare("INSERT INTO audit_log (user_id, action, ip_address) VALUES (?, ?, ?)");
    $s->bind_param("iss", $userId, $action, $ip);
    $s->execute();
    $s->close();
}

// ---------- Log a user in ----------
function login_user($conn, $user) {
    session_regenerate_id(true);                 // new ID, old one deleted (stops session fixation)
    $_SESSION['user_id']       = (int)$user['user_id'];
    $_SESSION['username']      = $user['username'];
    $_SESSION['role']          = $user['role'];
    $_SESSION['last_activity'] = time();
    $_SESSION['prev_login']    = $user['last_login'];   // show "last login" before overwriting it
    $_SESSION['csrf_token']    = bin2hex(random_bytes(32));

    $s = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $s->bind_param("i", $_SESSION['user_id']);
    $s->execute();
    $s->close();
}

function dashboard_for($role) {
    return $role === 'admin' ? 'admin_dashboard.php' : 'student_dashboard.php';
}

// ---------- Remember-me ----------
function set_remember_cookie($conn, $userId) {
    $selector  = bin2hex(random_bytes(12));      // 24 hex characters
    $validator = bin2hex(random_bytes(32));      // secret part
    $hash      = hash('sha256', $validator);     // only the hash goes to the DB

    $s = $conn->prepare("INSERT INTO remember_tokens (user_id, selector, token_hash, expires_at)
                         VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL " . REMEMBER_DAYS . " DAY))");
    $s->bind_param("iss", $userId, $selector, $hash);
    $s->execute();
    $s->close();

    setcookie('remember_me', $selector . ':' . $validator, [
        'expires'  => time() + REMEMBER_DAYS * 86400,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function expire_remember_cookie() {
    setcookie('remember_me', '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true]);
}

function clear_remember($conn) {
    if (!empty($_COOKIE['remember_me'])) {
        $selector = explode(':', $_COOKIE['remember_me'])[0];
        $s = $conn->prepare("DELETE FROM remember_tokens WHERE selector = ?");
        $s->bind_param("s", $selector);
        $s->execute();
        $s->close();
    }
    expire_remember_cookie();
}

function check_remember($conn) {
    if (empty($_COOKIE['remember_me'])) return false;
    $parts = explode(':', $_COOKIE['remember_me']);
    if (count($parts) !== 2) { expire_remember_cookie(); return false; }
    [$selector, $validator] = $parts;

    $s = $conn->prepare("SELECT t.token_id, t.token_hash, u.user_id, u.username, u.role, u.last_login
                         FROM remember_tokens t JOIN users u ON u.user_id = t.user_id
                         WHERE t.selector = ? AND t.expires_at > NOW()");
    $s->bind_param("s", $selector);
    $s->execute();
    $row = $s->get_result()->fetch_assoc();
    $s->close();

    if ($row && hash_equals($row['token_hash'], hash('sha256', $validator))) {
        $d = $conn->prepare("DELETE FROM remember_tokens WHERE token_id = ?");   // one-time use
        $d->bind_param("i", $row['token_id']);
        $d->execute();
        $d->close();

        login_user($conn, $row);
        set_remember_cookie($conn, (int)$row['user_id']);                        // issue a fresh token
        log_event($conn, (int)$row['user_id'], 'LOGIN_REMEMBER');
        return true;
    }
    expire_remember_cookie();
    return false;
}

// ---------- Destroy session completely ----------
function destroy_session() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

// ---------- Guard for protected pages ----------
function require_login($conn, $role = null) {
    if (isset($_SESSION['user_id'])) {
        if (time() - ($_SESSION['last_activity'] ?? 0) > SESSION_TIMEOUT) {
            log_event($conn, $_SESSION['user_id'], 'SESSION_TIMEOUT');
            destroy_session();
            header('Location: login.php?timeout=1');
            exit;
        }
        $_SESSION['last_activity'] = time();      // user is active, so reset the timer
    } elseif (!check_remember($conn)) {
        header('Location: login.php?denied=1');   // not logged in
        exit;
    }

    if ($role !== null && $_SESSION['role'] !== $role) {
        log_event($conn, $_SESSION['user_id'], 'UNAUTHORIZED_ACCESS');
        http_response_code(403);
        echo "<h2>403 - Access denied</h2><p>You do not have permission to view this page.</p>";
        echo "<p><a href='" . e(dashboard_for($_SESSION['role'])) . "'>Go to your dashboard</a></p>";
        exit;
    }
}

function logout_button() {
    return '<form method="POST" action="logout.php" style="display:inline">
              <input type="hidden" name="csrf_token" value="' . e($_SESSION['csrf_token']) . '">
              <button type="submit">Logout</button>
            </form>';
}