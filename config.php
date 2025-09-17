<?php
session_start();

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'notes_app';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo 'Failed to connect to MySQL: ' . $mysqli->connect_error;
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


