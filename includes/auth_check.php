<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';

// Start the session if the current page has not already started it.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect guests to the login page before protected page content is rendered.
if (!isset($_SESSION['user_id'])) {
    // Build a relative path back to /auth/login.php from the current folder depth.
    header('Location: ' . str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/', 1) - 1) . 'auth/login.php');
    exit;
}

// Check whether the logged-in user has the administrator role.
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
}

// Stop non-admin users from accessing administrator-only pages/actions.
function requireAdmin() {
    if (!isAdmin()) {
        // Store a flash message so the user understands why they were redirected.
        $_SESSION['flash'] = [
            'type'    => 'error',
            'message' => 'Access denied. You must be an administrator to perform this action.'
        ];

        // Send the user back to the list page.
        header('Location: list_users.php');
        exit;
    }
}