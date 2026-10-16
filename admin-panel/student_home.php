<?php
require_once 'config.php';
require_once 'functions.php';
require_login($conn, 'student');
$pageTitle = 'My Home';

// Link the user to the students table through the shared email address
$s = $conn->prepare(
    "SELECT ev.title, ev.event_date, r.registered_at
     FROM registrations r
     JOIN students s ON s.student_id = r.student_id
     JOIN events ev  ON ev.event_id  = r.event_id
     JOIN users u    ON u.email = s.email
     WHERE u.user_id = ? ORDER BY r.registered_at DESC");
$uid = (int)$_SESSION['user_id'];
$s->bind_param('i', $uid);
$s->execute();
$mine = $s->get_result();

require 'header.php';
?>
<h3>Welcome, <?= e($_SESSION['username']) ?></h3>
<p class="text-muted">You are logged in as a <b>student</b>. Admin pages are hidden from your menu and blocked on the server.</p>
<div class="card shadow-sm"><div class="card-body">
  <h5>My event registrations</h5>
  <?php if ($mine->num_rows === 0): ?>
    <p class="text-muted mb-0">You have not registered for any event yet.</p>
  <?php else: ?>
  <table class="table table-sm mb-0">
    <thead><tr><th>Event</th><th>Event date</th><th>Registered on</th></tr></thead>
    <tbody>
    <?php while ($r = $mine->fetch_assoc()): ?>
      <tr><td><?= e($r['title']) ?></td><td><?= e($r['event_date']) ?></td><td><?= e($r['registered_at']) ?></td></tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div></div>
<?php require 'footer.php'; ?>