<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok()) {
    if (!empty($_SESSION['user_id'])) {
        audit($conn, (int)$_SESSION['user_id'], 'LOGOUT', 'User logged out');
    }
    destroy_session();
    header('Location: login.php?loggedout=1');
    exit;
}
header('Location: login.php');
exit;