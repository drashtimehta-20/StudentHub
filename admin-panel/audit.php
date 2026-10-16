<?php
require_once 'config.php';
require_once 'functions.php';
require_login($conn, 'admin');
$pageTitle = 'Audit Logs';

$actions = array_column($conn->query("SELECT DISTINCT action FROM audit_logs ORDER BY action")->fetch_all(MYSQLI_ASSOC), 'action');

$filter = $_GET['action'] ?? '';
if (!in_array($filter, $actions, true)) $filter = '';

$sql = "SELECT l.log_id, l.action, l.details, l.ip_address, l.created_at, u.username
        FROM audit_logs l LEFT JOIN users u ON u.user_id = l.user_id"
     . ($filter !== '' ? " WHERE l.action = ?" : "")
     . " ORDER BY l.log_id DESC LIMIT 100";
$stmt = $conn->prepare($sql);
if ($filter !== '') $stmt->bind_param('s', $filter);
$stmt->execute();
$rows = $stmt->get_result();

function badge($a) {
    if (in_array($a, ['LOGIN_SUCCESS', 'USER_ACTIVATED'], true)) return 'success';
    if (in_array($a, ['LOGIN_FAILED', 'LOGIN_BLOCKED', 'UNAUTHORIZED_ACCESS', 'USER_DEACTIVATED', 'FORCED_LOGOUT'], true)) return 'danger';
    return 'secondary';
}

require 'header.php';
?>
<h3 class="mb-3">Audit Logs</h3>
<form method="GET" class="row g-2 mb-3">
  <div class="col-md-4">
    <select name="action" class="form-select">
      <option value="">All actions</option>
      <?php foreach ($actions as $a): ?>
        <option value="<?= e($a) ?>" <?= $filter === $a ? 'selected' : '' ?>><?= e($a) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-auto"><button class="btn btn-dark">Filter</button> <a href="audit.php" class="btn btn-outline-secondary">Reset</a></div>
</form>
<p class="text-muted">Showing the latest <?= (int)$rows->num_rows ?> entries (maximum 100).</p>
<div class="table-responsive">
<table class="table table-bordered table-sm bg-white">
  <thead class="table-dark"><tr><th>#</th><th>When</th><th>User</th><th>Action</th><th>Details</th><th>IP</th></tr></thead>
  <tbody>
  <?php if ($rows->num_rows === 0): ?><tr><td colspan="6" class="text-center text-muted">No entries.</td></tr><?php endif; ?>
  <?php while ($r = $rows->fetch_assoc()): ?>
    <tr>
      <td><?= (int)$r['log_id'] ?></td>
      <td><?= e($r['created_at']) ?></td>
      <td><?= e($r['username'] ?? '-') ?></td>
      <td><span class="badge bg-<?= badge($r['action']) ?>"><?= e($r['action']) ?></span></td>
      <td><?= e($r['details']) ?></td>
      <td><?= e($r['ip_address']) ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</div>
<?php require 'footer.php'; ?>