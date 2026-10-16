<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['csrf_token'], $_SESSION['csrf_token'])
    && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

    if (isset($_SESSION['user_id'])) {
        log_event($conn, $_SESSION['user_id'], 'LOGOUT');
    }
    clear_remember($conn);      
    destroy_session();          
    header('Location: login.php?loggedout=1');
    exit;
}

header('Location: login.php');
exit;