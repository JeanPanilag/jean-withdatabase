<?php
session_start();

// Connect using environment variables (Coolify-compatible)
$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USERNAME');
$dbPass = getenv('DB_PASSWORD');
$dbName = getenv('DB_DATABASE');
$dbPort = getenv('DB_PORT') ?: '3306';

// Suppress mysqli exceptions for cleaner error handling
mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
if (!$mysqli) {
    http_response_code(500);
    die('Connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($mysqli, 'utf8mb4');

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

