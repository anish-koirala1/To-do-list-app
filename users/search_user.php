<?php
/**
 * TD-166: Find User by Email/Name
 *
 * Dedicated search endpoint. Accepts a GET ?q= parameter
 * and forwards to list_users.php with the search param applied.
 * Can also be used as an AJAX endpoint returning JSON.
 */
require_once '../includes/auth_check.php';
requireAdmin();
require_once '../config/database.php';

$query  = trim($_GET['q'] ?? $_GET['search'] ?? '');
$format = $_GET['format'] ?? 'html';

/* ---- AJAX / JSON response ---- */
if ($format === 'json') {
    header('Content-Type: application/json; charset=utf-8');

    if ($query === '') {
        echo json_encode(['results' => []]);
        exit;
    }

    $pdo  = getDB();
    $stmt = $pdo->prepare(
        'SELECT id AS user_id, full_name, username, email, role, is_active
         FROM users
         WHERE full_name LIKE ? OR email LIKE ?
         ORDER BY full_name ASC
         LIMIT 20'
    );
    $stmt->execute(['%' . $query . '%', '%' . $query . '%']);
    $rows = $stmt->fetchAll();

    echo json_encode(['results' => $rows]);
    exit;
}

/* ---- HTML: redirect to list with search param ---- */
$params = http_build_query(array_filter(['search' => $query]));
header('Location: list_users.php' . ($params ? '?' . $params : ''));
exit;
