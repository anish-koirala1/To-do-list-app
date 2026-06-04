<?php
/**
 * Reports Controller - reports/index.php
 *
 * Routes: index (list), create, edit, delete, track (staff progress dashboard).
 * Students see only their own reports (read-only).
 */
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/model/Report.php';

$pdo = getDB();
$model = new Report($pdo);
$action = $_GET['action'] ?? 'index';
$baseUrl = '../';

// Staff-only: aggregate completion stats and report list
if ($action === 'track') {
    if (isStudent()) {
        redirectWithFlash('index.php', 'error', 'Progress tracking is for teachers and administrators.');
    }
    $pageTitle = 'Track Progress';
    $stats = $model->progressStats();
    $reports = $model->getAll();
    require __DIR__ . '/views/track.php';
    exit;
}

// Staff-only: delete by query string id
if ($action === 'delete' && isset($_GET['id'])) {
    requireStaff();
    $model->delete((int)$_GET['id']);
    redirectWithFlash('index.php', 'success', 'Report deleted.');
}

// Staff-only: create from POST
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireStaff();
    $model->create([
        'user_id' => currentUserId(),
        'title' => trim($_POST['title'] ?? ''),
        'subject' => trim($_POST['subject'] ?? ''),
        'priority' => $_POST['priority'] ?? 'Medium',
        'assign_date' => $_POST['assign_date'] ?? date('Y-m-d'),
        'due_date' => $_POST['due_date'] ?? date('Y-m-d'),
        'status' => $_POST['status'] ?? 'Pending',
    ]);
    redirectWithFlash('index.php', 'success', 'Report created.');
}

// Staff-only: edit form and POST update
if ($action === 'edit' && isset($_GET['id'])) {
    requireStaff();
    $id = (int)$_GET['id'];
    $report = $model->getById($id);
    if (!$report) {
        redirectWithFlash('index.php', 'error', 'Report not found.');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model->update($id, [
            'title' => trim($_POST['title'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'priority' => $_POST['priority'] ?? 'Medium',
            'assign_date' => $_POST['assign_date'] ?? '',
            'due_date' => $_POST['due_date'] ?? '',
            'status' => $_POST['status'] ?? 'Pending',
        ]);
        redirectWithFlash('index.php', 'success', 'Report updated.');
    }
    $pageTitle = 'Edit Report';
    require __DIR__ . '/views/form.php';
    exit;
}

// Staff-only: empty create form
if ($action === 'create') {
    requireStaff();
    $pageTitle = 'Create Report';
    $report = ['title' => '', 'subject' => '', 'priority' => 'Medium', 'assign_date' => date('Y-m-d'), 'due_date' => date('Y-m-d'), 'status' => 'Pending'];
    require __DIR__ . '/views/form.php';
    exit;
}

// Default: list with optional search/filters; students scoped to own user_id
$pageTitle = 'Reports';
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$priority = $_GET['priority'] ?? '';
$ownerId = isStudent() ? currentUserId() : null;
$reports = $model->getAll($search ?: null, $status ?: null, $priority ?: null, $ownerId);
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require __DIR__ . '/views/index.php';
