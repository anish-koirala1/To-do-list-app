<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/model/Report.php';

$pdo = getDB();
$model = new Report($pdo);
$action = $_GET['action'] ?? 'index';
$baseUrl = '../';

if ($action === 'track') {
    $pageTitle = 'Track Progress';
    $stats = $model->progressStats();
    $reports = $model->getAll();
    require __DIR__ . '/views/track.php';
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $model->delete((int)$_GET['id']);
    redirectWithFlash('index.php', 'success', 'Report deleted.');
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
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

if ($action === 'edit' && isset($_GET['id'])) {
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

if ($action === 'create') {
    $pageTitle = 'Create Report';
    $report = ['title' => '', 'subject' => '', 'priority' => 'Medium', 'assign_date' => date('Y-m-d'), 'due_date' => date('Y-m-d'), 'status' => 'Pending'];
    require __DIR__ . '/views/form.php';
    exit;
}

$pageTitle = 'Reports';
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$priority = $_GET['priority'] ?? '';
$reports = $model->getAll($search ?: null, $status ?: null, $priority ?: null);
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require __DIR__ . '/views/index.php';
