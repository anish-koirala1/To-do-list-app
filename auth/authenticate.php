<?php
// Start the session so we can store login errors and authenticated user data.
session_start();

// Authentication should only happen from the login form POST request.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Load the shared PDO database connection helper.
require_once '../config/database.php';

// Read and normalize submitted credentials.
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Reject empty credentials before querying the database.
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Email and password are required.';
    header('Location: login.php');
    exit;
}

// Validate email format to avoid unnecessary database work.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Please enter a valid email address.';
    header('Location: login.php');
    exit;
}

// Find the user account by email using a prepared statement.
$pdo  = getDB();
$stmt = $pdo->prepare('SELECT user_id, full_name, email, password, role, is_active FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

// Verify both the account exists and the password matches the stored hash.
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Invalid email or password.';
    header('Location: login.php');
    exit;
}

// Block disabled accounts from logging in.
if (!$user['is_active']) {
    $_SESSION['login_error'] = 'Your account has been disabled. Please contact the administrator.';
    header('Location: login.php');
    exit;
}

// Regenerate the session ID after login to protect against session fixation.
session_regenerate_id(true);

// Store the minimum user information needed across protected pages.
$_SESSION['user_id']   = $user['user_id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role']      = $user['role'];

// Send the authenticated user to the main dashboard.
header('Location: ../users/list_users.php');
exit;
