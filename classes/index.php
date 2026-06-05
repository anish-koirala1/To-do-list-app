<?php
/**
 * Scheduled Classes Controller - classes/index.php
 *
 * Routes: index (list), create, edit, delete.
 * Students see only classes where user_id matches their account.
 */
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/validate_academics.php';
require_once __DIR__ . '/model/ScheduledClass.php';

$pdo = getDB();
$model = new ScheduledClass($pdo);
$action = $_GET['action'] ?? 'index';
$uid = isStudent() ? currentUserId() : null;
$errors = [];

if ($action === 'delete' && isset($_GET['id'])) {
    requireStaff();
    $model->delete((int)$_GET['id']);
    redirectWithFlash('index.php', 'success', 'Class deleted.');
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireStaff();
    $post = $_POST;
    if (empty($post['user_id'])) {
        $post['user_id'] = currentUserId();
    }
    $validated = validateClassData($post, $pdo);
    $errors = $validated['errors'];
    $row = $validated['data'];

    if (empty($errors)) {
        $model->create([
            'report_id'   => $row['report_id'],
            'user_id'     => $row['user_id'] ?: currentUserId(),
            'title'       => $row['title'],
            'instructor'  => $row['instructor'],
            'classroom'   => $row['classroom'],
            'start_time'  => $row['start_time'],
            'end_time'    => $row['end_time'],
        ]);
        redirectWithFlash('index.php', 'success', 'Class scheduled.');
    }

    $pageTitle = 'Schedule Class';
    $reports = $model->reportOptions();
    require __DIR__ . '/views/form.php';
    exit;
}

if ($action === 'edit' && isset($_GET['id'])) {
    requireStaff();
    $id = (int)$_GET['id'];
    $row = $model->getById($id);
    if (!$row) redirectWithFlash('index.php', 'error', 'Class not found.');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validated = validateClassData($_POST, $pdo);
        $errors = $validated['errors'];
        $row = array_merge($row, $validated['data']);

        if (empty($errors)) {
            $model->update($id, [
                'report_id'   => $validated['data']['report_id'],
                'title'       => $validated['data']['title'],
                'instructor'  => $validated['data']['instructor'],
                'classroom'   => $validated['data']['classroom'],
                'start_time'  => $validated['data']['start_time'],
                'end_time'    => $validated['data']['end_time'],
            ]);
            redirectWithFlash('index.php', 'success', 'Class updated.');
        }
    }
    $pageTitle = 'Edit Class';
    $reports = $model->reportOptions();
    require __DIR__ . '/views/form.php';
    exit;
}

if ($action === 'create') {
    requireStaff();
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
