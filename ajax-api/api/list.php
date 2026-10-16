<?php
require_once __DIR__ . '/bootstrap.php';
require_method('GET');

if (DEMO_DELAY_MS > 0) usleep(DEMO_DELAY_MS * 1000);

$conn = db();

// Read and check query parameters
$q = $_GET['q'] ?? '';
$course = $_GET['course'] ?? '';
if (!is_string($q) || !is_string($course)) fail(400, 'Invalid query parameters.');
$q = trim($q); $course = trim($course);
if (mb_strlen($q) > 50) fail(400, 'Search text is too long (max 50 characters).');

$where = []; $types = ''; $params = [];

if ($q !== '') {
    $like = '%' . addcslashes($q, '%_\\') . '%';
    $where[] = '(name LIKE ? OR email LIKE ? OR course LIKE ?)';
    $types .= 'sss';
    array_push($params, $like, $like, $like);
}
if ($course !== '') {
    if (!in_array($course, COURSES, true)) fail(400, 'Invalid course filter.');
    $where[] = 'course = ?';
    $types .= 's';
    $params[] = $course;
}

$sql = 'SELECT id, name, email, phone, course, created_at FROM students'
     . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
     . ' ORDER BY id DESC LIMIT 100';

$stmt = $conn->prepare($sql);
if ($types !== '') $stmt->bind_param($types, ...$params);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

json_out(200, ['success' => true, 'count' => count($rows), 'data' => $rows]);