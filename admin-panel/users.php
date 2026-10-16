<?php
require_once 'config.php';
require_once 'functions.php';
require_login($conn, 'admin');
$pageTitle = 'Manage Users';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { http_response_code(403); die('Invalid request (CSRF check failed).'); }

    $id  = (int)($_POST['id'] ?? 0);
    $new = $_POST['status'] ?? '';

    if (!in_array($new, ['active', 'inactive'], true)) {
        set_flash('error', 'Invalid status.');
    } elseif ($id === (int)$_SESSION['user_id']) {
        set_flash('error', 'You cannot change the status of your own account.');
    } else {
        $s = $conn->prepare("SELECT username, status FROM users WHERE user_id = ?");
        $s->bind_param('i', $id);
        $s->execute();
        $target = $s->get_result()->fetch_assoc();
        $s->close();

        if (!$target) {
            set_flash('error', 'User not found.');
        } elseif ($target['status'] === $new) {
            set_flash('error', "{$target['username']} is already $new.");
        } else {
            $u = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
            $u->bind_param('si', $new, $id);
            $u->execute();
            $u->close();

            audit($conn, (int)$_SESSION['user_id'],
                  $new === 'active' ? 'USER_ACTIVATED' : 'USER_DEACTIVATED',
                  "Admin '{$_SESSION['username']}' set user '{$target['username']}' (id $id) to $new");
            set_flash('success', "User '{$target['username']}' is now $new.");
        }
    }
    header('Location: users.php');      // Post/Redirect/Get
    exit;
}

$users = $conn->query("SELECT user_id, username, email, role, status, last_login FROM users ORDER BY user_id");
require 'header.php';
?>
<h3 class="mb-3">Manage Users</h3>
<div class="table-responsive">
<table class="table table-bordered align-middle bg-white">
  <thead class="table-dark"><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Last login</th><th>Action</th></tr></thead>
  <tbody>
  <?php while ($r = $users->fetch_assoc()): $self = (int)$r['user_id'] === (int)$_SESSION['user_id']; ?>
    <tr>
      <td><?= (int)$r['user_id'] ?></td>
      <td><?= e($r['username']) ?><?= $self ? ' <span class="text-muted small">(you)</span>' : '' ?></td>
      <td><?= e($r['email']) ?></td>
      <td><?= e($r['role']) ?></td>
      <td><span class="badge bg-<?= $r['status'] === 'active' ? 'success' : 'secondary' ?>"><?= e(ucfirst($r['status'])) ?></span></td>
      <td><?= e($r['last_login'] ?? 'Never') ?></td>
      <td>
        <?php if ($self): ?>
          <span class="text-muted small">-</span>
        <?php else: $to = $r['status'] === 'active' ? 'inactive' : 'active'; ?>
          <form method="POST" action="users.php" class="m-0"
                onsubmit="return confirm('Set <?= e(addslashes($r['username'])) ?> to <?= $to ?>?');">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$r['user_id'] ?>">
            <input type="hidden" name="status" value="<?= $to ?>">
            <button class="btn btn-sm btn-<?= $to === 'inactive' ? 'danger' : 'success' ?>">
              <?= $to === 'inactive' ? 'Deactivate' : 'Activate' ?>
            </button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</div>
<?php require 'footer.php'; ?>