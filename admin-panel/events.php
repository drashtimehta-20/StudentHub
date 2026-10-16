<?php
require_once 'config.php';
require_once 'functions.php';
require_login($conn);                       // admin AND student may open this page
$pageTitle = 'Events';

$rows = $conn->query(
    "SELECT ev.title, ev.category, ev.event_date, ev.venue, COUNT(r.registration_id) AS registered
     FROM events ev LEFT JOIN registrations r ON r.event_id = ev.event_id
     GROUP BY ev.event_id ORDER BY ev.event_date");
require 'header.php';
?>
<h3 class="mb-3">Events</h3>
<div class="table-responsive">
<table class="table table-bordered bg-white">
  <thead class="table-dark"><tr><th>Title</th><th>Category</th><th>Date</th><th>Venue</th><th>Registered</th></tr></thead>
  <tbody>
  <?php while ($r = $rows->fetch_assoc()): ?>
    <tr><td><?= e($r['title']) ?></td><td><?= e($r['category']) ?></td><td><?= e($r['event_date']) ?></td>
        <td><?= e($r['venue']) ?></td><td><?= (int)$r['registered'] ?></td></tr>
  <?php endwhile; ?>
  </tbody>
</table>
</div>
<?php require 'footer.php'; ?>