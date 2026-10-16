<?php
require_once __DIR__ . '/bootstrap.php';
require_method('POST');

$input = read_json_body();
$id    = get_id($input['id'] ?? null);

$conn = db();
$stmt = $conn->prepare('DELETE FROM students WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$deleted = $stmt->affected_rows > 0;
$stmt->close();

if (!$deleted) fail(404, 'Student not found.');

json_out(200, ['success' => true, 'message' => 'Student deleted successfully.']);