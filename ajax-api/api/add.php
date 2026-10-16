<?php
require_once __DIR__ . '/bootstrap.php';
require_method('POST');

$input = read_json_body();
[$data, $errors] = validate_student($input);

if ($errors) fail(422, 'Please fix the errors below.', $errors);

$conn = db();

if (email_taken($conn, $data['email'])) {
    fail(409, 'This email is already registered.', ['email' => 'This email is already registered.']);
}

try {
    $stmt = $conn->prepare('INSERT INTO students (name, email, phone, course) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $data['name'], $data['email'], $data['phone'], $data['course']);
    $stmt->execute();
    $newId = $conn->insert_id;
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {      // two requests at the same moment: the UNIQUE key blocks one
        fail(409, 'This email is already registered.', ['email' => 'This email is already registered.']);
    }
    throw $e;                          // anything else goes to the central handler
}

json_out(201, ['success' => true, 'message' => 'Student added successfully.', 'data' => ['id' => $newId] + $data]);