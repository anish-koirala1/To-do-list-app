<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $depth = max(0, substr_count($_SERVER['PHP_SELF'], '/') - 1);
    header('Location: ' . str_repeat('../', $depth) . 'auth/login.php');
    exit;
}
