<?php
/**
 * Authentication Check - auth_check.php
 * 
 * Middleware that verifies a user is logged in before allowing access to protected pages.
 * This file should be included at the top of any page that requires authentication.
 * 
 * Behavior:
 * - Checks if a session exists, creates one if not
 * - Redirects unauthenticated users to the login page
 * - Automatically determines the correct relative path to auth/login.php
 */

// Load database config and helper functions
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helpers.php';

// Start a session if one doesn't already exist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user_id is in session (indicates they are logged in)
if (!isset($_SESSION['user_id'])) {
    // Calculate the relative path to auth/login.php based on current file depth
    $depth = max(0, substr_count($_SERVER['PHP_SELF'], '/') - 1);
    header('Location: ' . str_repeat('../', $depth) . 'auth/login.php');
    exit;
}
