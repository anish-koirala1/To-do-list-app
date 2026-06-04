<?php
/**
 * Authentication Handler - authenticate.php
 * 
 * Processes user login credentials submitted via POST request from login.php.
 * Validates credentials against the database and establishes a secure session
 * if authentication is successful.
 * 
 * Security Features:
 * - Session regeneration after login to prevent session fixation attacks
 * - Checks for disabled accounts before allowing login
 * - Password verification using PHP's password_verify() hash comparison
 * - Requires POST method to prevent login via GET requests
 */

// Start the session so we can store login errors and authenticated user data
session_start();

// Authentication should only happen from the login form POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Load the shared PDO database connection helper
require_once '../config/database.php';
// Load the user lookup function
require_once '../includes/ensure_users_schema.php';

// Read and normalize submitted credentials (trim whitespace from email)
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Reject empty credentials before querying the database (prevents database lookups for empty inputs)
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Email and password are required.';
    header('Location: login.php');
    exit;
}

// Get the database connection
$pdo  = getDB();
// Look up the user by email or username
$user = findUserForLogin($pdo, $email);

// Verify both the account exists and the password matches the stored hash
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Invalid email or password.';
    header('Location: login.php');
    exit;
}

// Block disabled accounts from logging in
if (!$user['is_active']) {
    $_SESSION['login_error'] = 'Your account has been disabled. Please contact the administrator.';
    header('Location: login.php');
    exit;
}

// Regenerate the session ID after login to protect against session fixation attacks
session_regenerate_id(true);

// Store the minimum user information needed across protected pages
$_SESSION['user_id']   = $user['user_id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role']      = $user['role'];

// Send the user to their role-specific dashboard
header('Location: ../dashboard/index.php');
exit;