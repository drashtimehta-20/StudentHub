<?php
require_once 'config.php';
require_once 'functions.php';
require_login($conn, 'admin');
$pageTitle = 'Admin Dashboard';

// ---- 1. Statistics (SQL aggregation) ----
$u = $conn->query(
    "SELECT COUNT(*) AS total,
            COALESCE(SUM(status = 'active'), 0)   AS active,
            COALESCE(SUM(status = 'inactive'), 0) AS inactive,
            COALESCE(SUM(role = 'admin'), 0)      AS admins
     FROM users")->fetch_assoc();
$studentCount = (int)$conn->query("SELECT COUNT(*) FROM students")->fetch_row()[0];
$eventCount   = (int)$conn->query("SELECT COUNT(*) FROM events")->fetch_row()[0];
$regCount     = (int)$conn->query("SELECT COUNT(*) FROM registrations")->fetch_row()[0];

// ---- 2. Latest registrations (JOIN) ----
$latest = $conn->query(
    "SELECT s.full_name, s.course, ev.title, r.registered_at
     FROM registrations r
     JOIN students s ON s.student_id = r.student_id
     JOIN events ev  ON ev.event_id  = r.event_id
     ORDER BY r.registered_at DESC, r.registration_id DESC LIMIT 5");

// ---- 3. Registrations per event (GROUP BY) ----
$perEvent = $conn->query(
    "SELECT ev.title, COUNT(r.registration_id) AS total
     FROM events ev LEFT JOIN registrations r ON r.event_id = ev.event_id
     GROUP BY ev.event_id, ev.title ORDER BY total DESC, ev.title LIMIT 5");

// ---- 4. Recent admin activity ----
$recent = $conn->query(
    "SELECT l.action, l.details, l.created_at, u.username
     FROM audit_logs l LEFT JOIN users u ON u.user_id = l.user_id
     ORDER BY l.log_id DESC LIMIT 5");

// ---- 5. Registrations by month for the last 12 months (chart) ----
$res    = $conn->query(
    "SELECT DATE_FORMAT(registered_at, '%Y-%m') AS ym, COUNT(*) AS c
     FROM registrations
     WHERE registered_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%Y-%m-01')
     GROUP BY ym");
$counts = array_column($res->fetch_all(MYSQLI_ASSOC), 'c', 'ym');
$labels = []; $values = [];
for ($i = 11; $i >= 0; $i--) {          // fill months that have no registrations with 0
    $d = new DateTime('first day of this month');
    $d->modify("-$i months");
    $labels[] = $d->format('M Y');
    $values[] = (int)($counts[$d->format('Y-m')] ?? 0);
}

require 'header.php';
?>
<h3 class="mb-3">Admin Dashboard</h3>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="card text-bg-primary h-100"><div class="card-body">
      <div class="small">Total users</div><div class="display-6"><?= (int)$u['total'] ?></div>
      <div class="small"><?= (int)$u['active'] ?> active / <?= (int)$u['inactive'] ?> inactive</div>
  </div></div></div>
  <div class="col-6 col-lg-3"><div class="card text-bg-success h-100"><div class="card-body">
      <div class="small">Students</div><div class="display-6"><?= $studentCount ?></div>
      <div class="small">Admins: <?= (int)$u['admins'] ?></div>
  </div></div></div>
  <div class="col-6 col-lg-3"><div class="card text-bg-warning h-100"><div class="card-body">
      <div class="small">Events</div><div class="display-6"><?= $eventCount ?></div>
  </div></div></div>
  <div class="col-6 col-lg-3"><div class="card text-bg-danger h-100"><div class="card-body">
      <div class="small">Registrations</div><div class="display-6"><?= $regCount ?></div>
  </div></div></div>
</div>

<div class="card shadow-sm mb-4"><div class="card-body">
  <h5>Event registrations by month</h5>
  <div style="height:300px"><canvas id="regChart"></canvas></div>
  <p id="chartFallback" class="text-muted small d-none mb-0">The chart library could not load (no internet). Data: <?= e(implode(', ', array_map(fn($l, $v) => "$l = $v", $labels, $values))) ?></p>
</div></div>

<div class="row g-3">
  <div class="col-lg-7"><div class="card shadow-sm h-100"><div class="card-body">
    <h5>Latest registrations</h5>
    <div class="table-responsive"><table class="table table-sm table-striped mb-0">
      <thead><tr><th>Student</th><th>Course</th><th>Event</th><th>When</th></tr></thead>
      <tbody>
      <?php while ($r = $latest->fetch_assoc()): ?>
        <tr><td><?= e($r['full_name']) ?></td><td><?= e($r['course']) ?></td><td><?= e($r['title']) ?></td><td><?= e($r['registered_at']) ?></td></tr>
      <?php endwhile; ?>
      </tbody>
    </table></div>
  </div></div></div>

  <div class="col-lg-5"><div class="card shadow-sm h-100"><div class="card-body">
    <h5>Top events</h5>
    <ul class="list-group list-group-flush">
      <?php while ($r = $perEvent->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between"><?= e($r['title']) ?><span class="badge bg-dark"><?= (int)$r['total'] ?></span></li>
      <?php endwhile; ?>
    </ul>
  </div></div></div>
</div>

<div class="card shadow-sm mt-3"><div class="card-body">
  <div class="d-flex justify-content-between"><h5>Recent activity</h5><a href="audit.php">View all</a></div>
  <div class="table-responsive"><table class="table table-sm mb-0">
    <thead><tr><th>When</th><th>User</th><th>Action</th><th>Details</th></tr></thead>
    <tbody>
    <?php while ($r = $recent->fetch_assoc()): ?>
      <tr><td><?= e($r['created_at']) ?></td><td><?= e($r['username'] ?? '-') ?></td><td><?= e($r['action']) ?></td><td><?= e($r['details']) ?></td></tr>
    <?php endwhile; ?>
    </tbody>
  </table></div>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const labels = <?= json_encode($labels, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const values = <?= json_encode($values) ?>;
if (typeof Chart === 'undefined') {
    document.getElementById('chartFallback').classList.remove('d-none');
} else {
    new Chart(document.getElementById('regChart'), {
        type: 'bar',
        data: { labels: labels, datasets: [{ label: 'Registrations', data: values, backgroundColor: '#2c3e50' }] },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
}
</script>
<?php require 'footer.php'; ?>