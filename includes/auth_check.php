<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/', 1) - 1) . 'auth/login.php');
    exit;
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        $_SESSION['flash'] = [
            'type'    => 'error',
            'message' => 'Access denied. You must be an administrator to perform this action.'
        ];
        header('Location: list_users.php');
        exit;
    }
}
