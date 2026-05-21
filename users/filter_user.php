<?php
/**
 * TD-167: Filter by Role/Status
 *
 * Dedicated filter endpoint. Accepts GET ?role= and/or ?status= parameters
 * and forwards to list_users.php with filters applied.
 * Can also be used as an AJAX endpoint returning JSON.
 */
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$allowedRoles    = ['Admin', 'Teacher', 'Student'];
$allowedStatuses = ['1', '0'];

$role   = $_GET['role']   ?? '';
$status = $_GET['status'] ?? '';
$format = $_GET['format'] ?? 'html';

if (!in_array($role,   $allowedRoles,    true)) $role   = '';
if (!in_array($status, $allowedStatuses, true)) $status = '';

/* ---- AJAX / JSON response ---- */
if ($format === 'json') {
    header('Content-Type: application/json; charset=utf-8');

    $conditions = [];
    $params     = [];

    if ($role !== '') {
        $conditions[] = 'role = ?';
        $params[]     = $role;
    }

    if ($status !== '') {
        $conditions[] = 'is_active = ?';
        $params[]     = (int)$status;
    }

    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $pdo  = getDB();
    $stmt = $pdo->prepare(
        "SELECT user_id, full_name, email, role, is_active, created_date
         FROM users
         $where
         ORDER BY created_date DESC
         LIMIT 100"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    echo json_encode(['results' => $rows, 'count' => count($rows)]);
    exit;
}

/* ---- HTML: redirect to list with filter params ---- */
$params = http_build_query(array_filter(['role' => $role, 'status' => $status]));
header('Location: list_users.php' . ($params ? '?' . $params : ''));
exit;
