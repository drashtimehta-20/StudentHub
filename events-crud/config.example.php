<?php
session_name('sh_events');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli('localhost', 'root', '', 'event_db');
$conn->set_charset('utf8mb4');