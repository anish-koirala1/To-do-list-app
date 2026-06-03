<?php
require_once __DIR__ . '/../includes/auth_check.php';

$role = $_SESSION['role'] ?? 'Student';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = 'Dashboard';
$baseUrl = '../';

switch ($role) {
    case 'Admin':
        require __DIR__ . '/admin.php';
        break;
    case 'Teacher':
        require __DIR__ . '/teacher.php';
        break;
    default:
        require __DIR__ . '/student.php';
        break;
}
