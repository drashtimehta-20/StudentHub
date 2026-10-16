<?php
declare(strict_types=1);

const COURSES       = ['BCA', 'MCA', 'B.Tech', 'M.Tech', 'BSc IT'];
const DEMO_DELAY_MS = 400;   // makes the "Loading..." state visible on localhost; set to 0 later

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', '0');          // never print PHP errors into the JSON

// ---------- Central response helpers ----------
function json_out(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function fail(int $status, string $message, array $errors = []): void {
    json_out($status, ['success' => false, 'message' => $message, 'errors' => (object)$errors]);
}

// ---------- Centralized error handling (Advanced extension) ----------
set_error_handler(function (int $no, string $str, string $file, int $line) {
    if (!(error_reporting() & $no)) return false;
    throw new ErrorException($str, 0, $no, $file, $line);
});

set_exception_handler(function (Throwable $e) {
    error_log($e->getMessage());                      // details go to the private log
    fail(500, 'Server error. Please try again later.');   // the user sees nothing sensitive
});

// ---------- Request helpers ----------
function require_method(string $method): void {
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        header('Allow: ' . $method);
        fail(405, "Method not allowed. Use $method for this endpoint.");
    }
}

function read_json_body(): array {
    $type = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($type, 'application/json') === false) {
        fail(415, 'Content-Type must be application/json.');
    }
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        fail(400, 'Request body must be valid JSON.');
    }
    return $data;
}

function db(): mysqli {
    try {
        $conn = new mysqli('localhost', 'root', '', 'api_db');
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (mysqli_sql_exception $e) {
        error_log('DB connection failed: ' . $e->getMessage());
        fail(500, 'Database connection failed.');
    }
}

function get_id($value): int {
    if (is_int($value) || (is_string($value) && ctype_digit($value))) {
        $id = (int)$value;
        if ($id >= 1) return $id;
    }
    fail(400, 'A valid student id is required.');
}

// ---------- Validation (runs BEFORE any database operation) ----------
function validate_student(array $in): array {
    $data = []; $err = [];

    foreach (['name', 'email', 'phone', 'course'] as $k) {
        $v = $in[$k] ?? '';
        if (!is_string($v)) { $err[$k] = ucfirst($k) . ' must be text.'; $v = ''; }
        $data[$k] = trim(strip_tags($v));
    }

    if (!isset($err['name'])) {
        if ($data['name'] === '')                                   $err['name'] = 'Name is required.';
        elseif (!preg_match('/^[A-Za-z .]{3,50}$/', $data['name'])) $err['name'] = 'Name must be 3-50 letters (spaces and dots allowed).';
    }
    if (!isset($err['email'])) {
        if ($data['email'] === '')                                  $err['email'] = 'Email is required.';
        elseif (strlen($data['email']) > 100 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                                                                    $err['email'] = 'Enter a valid email address.';
    }
    if (!isset($err['phone'])) {
        if ($data['phone'] === '')                                  $err['phone'] = 'Phone is required.';
        elseif (!preg_match('/^[6-9][0-9]{9}$/', $data['phone']))   $err['phone'] = 'Phone must be 10 digits and start with 6-9.';
    }
    if (!isset($err['course']) && !in_array($data['course'], COURSES, true)) {
        $err['course'] = 'Please select a valid course.';
    }
    return [$data, $err];
}

function email_taken(mysqli $conn, string $email, int $ignoreId = 0): bool {
    $s = $conn->prepare('SELECT id FROM students WHERE email = ? AND id <> ?');
    $s->bind_param('si', $email, $ignoreId);
    $s->execute();
    $s->store_result();
    $found = $s->num_rows > 0;
    $s->close();
    return $found;
}