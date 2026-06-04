<?php
/**
 * Dashboard Router - dashboard/index.php
 *
 * Entry point after login. Loads the correct dashboard view based on session role:
 * Admin, Teacher, or Student (default).
 */
require_once __DIR__ . '/../includes/auth_check.php';

// Read role and one-time flash message from session
$role = $_SESSION['role'] ?? 'Student';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = 'Dashboard';
$baseUrl = '../';  // Relative path to app root for links in dashboard partials

// Route to the role-specific dashboard template
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
