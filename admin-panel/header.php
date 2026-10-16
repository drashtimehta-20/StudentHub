<?php $flash = get_flash(); $current = basename($_SERVER['SCRIPT_NAME']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Admin Panel') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <div class="container">
    <span class="navbar-brand">StudentHub Admin</span>
    <?php if (!empty($_SESSION['user_id'])): ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <?php foreach (menu_for($_SESSION['role']) as [$label, $url]): ?>
          <li class="nav-item">
            <a class="nav-link <?= $current === $url ? 'active fw-bold' : '' ?>" href="<?= e($url) ?>"><?= e($label) ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <span class="navbar-text me-3">
          <?= e($_SESSION['username']) ?>
          <span class="badge bg-<?= $_SESSION['role'] === 'admin' ? 'warning text-dark' : 'info text-dark' ?>"><?= e($_SESSION['role']) ?></span>
      </span>
      <form method="POST" action="logout.php" class="m-0">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <button class="btn btn-sm btn-outline-light">Logout</button>
      </form>
    </div>
    <?php endif; ?>
  </div>
</nav>
<main class="container pb-5">
<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>