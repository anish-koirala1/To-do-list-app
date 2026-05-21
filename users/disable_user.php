<?php
require_once '../includes/auth_check.php';
requireAdmin();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list_users.php');
    exit;
}

$userId   = (int)($_POST['user_id'] ?? 0);
$redirect = $_POST['redirect'] ?? 'list_users.php';

/* Only allow redirect to pages within the users/ directory */
$redirect = basename($redirect);
if (!preg_match('/^[a-z_]+\.php$/', $redirect)) {
    $redirect = 'list_users.php';
}

if ($userId <= 0) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid user ID.'];
    header('Location: ' . $redirect);
    exit;
}

/* Prevent admin from disabling their own account */
if ($userId === (int)$_SESSION['user_id']) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'You cannot disable your own account.'];
    header('Location: ' . $redirect);
    exit;
}

$pdo  = getDB();
$stmt = $pdo->prepare('SELECT user_id, full_name, is_active FROM users WHERE user_id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'User not found.'];
    header('Location: ' . $redirect);
    exit;
}

$newStatus = $user['is_active'] ? 0 : 1;
$upd = $pdo->prepare('UPDATE users SET is_active = ? WHERE user_id = ?');
$upd->execute([$newStatus, $userId]);

$action = $newStatus ? 'enabled' : 'disabled';
$_SESSION['flash'] = [
    'type'    => 'success',
    'message' => 'User "' . $user['full_name'] . '" was ' . $action . ' successfully.',
];

header('Location: ' . $redirect);
exit;
