<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/model/ScheduledClass.php';

$pdo = getDB();
$model = new ScheduledClass($pdo);
$action = $_GET['action'] ?? 'index';
$uid = isStudent() ? currentUserId() : null;

if ($action === 'delete' && isset($_GET['id'])) {
    $model->delete((int)$_GET['id']);
    redirectWithFlash('index.php', 'success', 'Class deleted.');
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->create([
        'report_id' => (int)($_POST['report_id'] ?? 0) ?: null,
        'user_id' => isStudent() ? currentUserId() : (int)($_POST['user_id'] ?? currentUserId()),
        'title' => trim($_POST['title'] ?? ''),
        'instructor' => trim($_POST['instructor'] ?? ''),
        'classroom' => trim($_POST['classroom'] ?? ''),
        'start_time' => $_POST['start_time'] ?? '',
        'end_time' => $_POST['end_time'] ?? '',
    ]);
    redirectWithFlash('index.php', 'success', 'Class scheduled.');
}

if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $row = $model->getById($id);
    if (!$row) redirectWithFlash('index.php', 'error', 'Class not found.');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model->update($id, [
            'report_id' => (int)($_POST['report_id'] ?? 0) ?: null,
            'title' => trim($_POST['title'] ?? ''),
            'instructor' => trim($_POST['instructor'] ?? ''),
            'classroom' => trim($_POST['classroom'] ?? ''),
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
        ]);
        redirectWithFlash('index.php', 'success', 'Class updated.');
    }
    $pageTitle = 'Edit Class';
    $reports = $model->reportOptions();
    require __DIR__ . '/views/form.php';
    exit;
}

if ($action === 'create') {
    $pageTitle = 'Schedule Class';
    $row = ['report_id' => '', 'title' => '', 'instructor' => '', 'classroom' => '', 'start_time' => '', 'end_time' => ''];
    $reports = $model->reportOptions();
    require __DIR__ . '/views/form.php';
    exit;
}

$pageTitle = 'Scheduled Classes';
$classes = $model->getAll($uid);
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require __DIR__ . '/views/index.php';
