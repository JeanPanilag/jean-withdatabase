<?php
session_start();

// Allow environment overrides for container/host setups
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = (int)(getenv('DB_PORT') ?: 3306);
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'notes_app';
$dbSocket = getenv('DB_SOCKET') ?: null; // e.g., /var/run/mysqld/mysqld.sock

// Suppress mysqli exceptions for cleaner error handling
mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = @new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort, $dbSocket ?: null);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo 'MySQL connection failed. Please verify DB settings (host/port/user/pass/name) and that MySQL is running.';
    exit;
}

$mysqli->set_charset('utf8mb4');

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

?>


