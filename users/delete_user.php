<?php
/**
 * Delete User - users/delete_user.php
 *
 * POST handler: permanently removes a user. Blocks self-delete and invalid redirects.
 */
require_once '../includes/auth_check.php';
requireAdmin();
require_once '../config/database.php';

// Only accept POST from list_users action forms
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

/* Prevent admin from deleting their own account */
if ($userId === (int)$_SESSION['user_id']) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'You cannot delete your own account.'];
    header('Location: ' . $redirect);
    exit;
}

$pdo  = getDB();
$stmt = $pdo->prepare('SELECT id, full_name FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'User not found.'];
    header('Location: ' . $redirect);
    exit;
}

$del = $pdo->prepare('DELETE FROM users WHERE id = ?');
$del->execute([$userId]);

$_SESSION['flash'] = [
    'type'    => 'success',
    'message' => 'User "' . $user['full_name'] . '" was permanently deleted.',
];

header('Location: ' . $redirect);
exit;
