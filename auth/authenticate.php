<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Email and password are required.';
    header('Location: login.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Please enter a valid email address.';
    header('Location: login.php');
    exit;
}

$pdo  = getDB();
$stmt = $pdo->prepare('SELECT user_id, full_name, email, password, role, is_active FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Invalid email or password.';
    header('Location: login.php');
    exit;
}

if (!$user['is_active']) {
    $_SESSION['login_error'] = 'Your account has been disabled. Please contact the administrator.';
    header('Location: login.php');
    exit;
}

session_regenerate_id(true);

$_SESSION['user_id']   = $user['user_id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role']      = $user['role'];

header('Location: ../users/list_users.php');
exit;
