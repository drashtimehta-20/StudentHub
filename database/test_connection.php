<?php
require_once 'db.php';
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

echo "<h2>Connection successful &#10004;</h2>";

$stmt = $pdo->prepare("SELECT full_name, email, course FROM students WHERE course = :course");
$stmt->execute([':course' => 'BCA']);
echo "<h3>BCA students</h3><ul>";
foreach ($stmt as $row) {
    echo "<li>" . e($row['full_name']) . " - " . e($row['email']) . "</li>";
}
echo "</ul>";

$sql = "SELECT s.full_name, ev.title, r.registered_at
        FROM registrations r
        JOIN students s ON s.student_id = r.student_id
        JOIN events ev  ON ev.event_id  = r.event_id
        ORDER BY ev.title";
$rows = $pdo->query($sql)->fetchAll();
echo "<h3>Registrations</h3><table border='1' cellpadding='6'>
      <tr><th>Student</th><th>Event</th><th>Date</th></tr>";
foreach ($rows as $r) {
    echo "<tr><td>" . e($r['full_name']) . "</td><td>" . e($r['title']) . "</td><td>" . e($r['registered_at']) . "</td></tr>";
}
echo "</table>";

try {
    $ins = $pdo->prepare("INSERT INTO students (full_name, email, mobile, course) VALUES (?, ?, ?, ?)");
    $ins->execute(['Test User', 'test' . time() . '@example.com', '9000000000', 'BCA']);
    echo "<p>New student inserted. ID = " . e($pdo->lastInsertId()) . "</p>";
} catch (PDOException $ex) {
    error_log($ex->getMessage());
    echo "<p>Could not insert record.</p>";
}