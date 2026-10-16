<?php
require_once __DIR__ . '/bootstrap.php';
require_method('POST');

$input = read_json_body();
$id    = get_id($input['id'] ?? null);
[$data, $errors] = validate_student($input);

if ($errors) fail(422, 'Please fix the errors below.', $errors);

$conn = db();

// Does this student exist?
$s = $conn->prepare('SELECT id FROM students WHERE id = ?');
$s->bind_param('i', $id);
$s->execute();
$s->store_result();
$exists = $s->num_rows > 0;
$s->close();
if (!$exists) fail(404, 'Student not found.');

if (email_taken($conn, $data['email'], $id)) {
    fail(409, 'This email is already registered.', ['email' => 'This email is already registered.']);
}

try {
    $stmt = $conn->prepare('UPDATE students SET name = ?, email = ?, phone = ?, course = ? WHERE id = ?');
    $stmt->bind_param('ssssi', $data['name'], $data['email'], $data['phone'], $data['course'], $id);
    $stmt->execute();
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        fail(409, 'This email is already registered.', ['email' => 'This email is already registered.']);
    }
    throw $e;
}

json_out(200, ['success' => true, 'message' => 'Student updated successfully.', 'data' => ['id' => $id] + $data]);