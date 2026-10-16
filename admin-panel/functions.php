<?php
const SESSION_TIMEOUT = 20;     // 10 minutes of inactivity

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// ---------- Flash messages ----------
function set_flash($type, $msg) { $_SESSION['flash'] = ['type' => $type, 'msg' => $msg]; }
function get_flash() {
    if (isset($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

// ---------- CSRF ----------
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_ok() {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

// ---------- Audit log ----------
function audit(mysqli $conn, ?int $userId, string $action, string $details = ''): void {
    $ip      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $details = mb_substr($details, 0, 255);
    $s = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
    $s->bind_param('isss', $userId, $action, $details, $ip);
    $s->execute();
    $s->close();
}

// ---------- Dynamic menu: each item lists the roles allowed to see it ----------
function menu_for(string $role): array {
    $all = [
        ['Dashboard',  'dashboard.php',    ['admin']],
        ['My Home',    'student_home.php', ['student']],
        ['Users',      'users.php',        ['admin']],
        ['Events',     'events.php',       ['admin', 'student']],
        ['Audit Logs', 'audit.php',        ['admin']],
    ];
    return array_values(array_filter($all, fn($m) => in_array($role, $m[2], true)));
}

function home_for(string $role): string {
    return $role === 'admin' ? 'dashboard.php' : 'student_home.php';
}

// ---------- Session helpers ----------
function destroy_session(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function forbid(mysqli $conn, string $why): void {
    audit($conn, (int)$_SESSION['user_id'], 'UNAUTHORIZED_ACCESS', $why);
    http_response_code(403);
    $pageTitle = 'Access denied';
    require __DIR__ . '/header.php';
    echo '<div class="alert alert-danger"><h4>403 - Access denied</h4>'
       . '<p class="mb-2">You do not have permission to view this page. This attempt has been recorded.</p>'
       . '<a class="btn btn-dark btn-sm" href="' . e(home_for($_SESSION['role'])) . '">Go to my home page</a></div>';
    require __DIR__ . '/footer.php';
    exit;
}

// ---------- Guard used at the top of every protected page ----------
function require_login(mysqli $conn, ?string $role = null): void {
    if (empty($_SESSION['user_id'])) {
        set_flash('error', 'Please log in to continue.');
        header('Location: login.php');
        exit;
    }
    $uid = (int)$_SESSION['user_id'];

    // 1. Timeout
    if (time() - ($_SESSION['last_activity'] ?? 0) > SESSION_TIMEOUT) {
        audit($conn, $uid, 'SESSION_TIMEOUT', 'No activity for ' . SESSION_TIMEOUT . ' seconds');
        destroy_session();
        header('Location: login.php?timeout=1');
        exit;
    }

    // 2. Re-check the database on every request, so deactivating a user takes effect immediately
    $s = $conn->prepare("SELECT role, status FROM users WHERE user_id = ?");
    $s->bind_param('i', $uid);
    $s->execute();
    $row = $s->get_result()->fetch_assoc();
    $s->close();

    if (!$row || $row['status'] !== 'active') {
        audit($conn, $row ? $uid : null, 'FORCED_LOGOUT', 'Account is inactive or was removed');
        destroy_session();
        header('Location: login.php?inactive=1');
        exit;
    }

    $_SESSION['role']          = $row['role'];     // role always comes from the database
    $_SESSION['last_activity'] = time();

    // 3. Role check
    if ($role !== null && $row['role'] !== $role) {
        forbid($conn, "Role '{$row['role']}' tried to open " . basename($_SERVER['SCRIPT_NAME']));
    }
}