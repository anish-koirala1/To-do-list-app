<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/model/Assignment.php';

$pdo = getDB();
$model = new Assignment($pdo);
$action = $_GET['action'] ?? 'index';

if ($action === 'delete' && isset($_GET['id']) && isTeacher()) {
    $model->delete((int)$_GET['id']);
    redirectWithFlash('index.php', 'success', 'Assignment deleted.');
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST' && isTeacher()) {
    $model->create([
        'teacher_id' => currentUserId(),
        'title' => trim($_POST['title'] ?? ''),
        'subject' => trim($_POST['subject'] ?? ''),
        'assign_date' => $_POST['assign_date'] ?? date('Y-m-d'),
        'due_date' => $_POST['due_date'] ?? date('Y-m-d'),
        'status' => $_POST['status'] ?? 'Open',
    ]);
    redirectWithFlash('index.php', 'success', 'Assignment created.');
}

if ($action === 'edit' && isset($_GET['id']) && isTeacher()) {
    $id = (int)$_GET['id'];
    $row = $model->getById($id);
    if (!$row) redirectWithFlash('index.php', 'error', 'Not found.');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model->update($id, [
            'title' => trim($_POST['title'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'assign_date' => $_POST['assign_date'] ?? '',
            'due_date' => $_POST['due_date'] ?? '',
            'status' => $_POST['status'] ?? 'Open',
        ]);
        redirectWithFlash('index.php', 'success', 'Assignment updated.');
    }
    $pageTitle = 'Edit Assignment';
    require __DIR__ . '/views/form.php';
    exit;
}

if ($action === 'submit' && isset($_GET['id']) && isStudent()) {
    $id = (int)$_GET['id'];
    $a = $model->getById($id);
    if (!$a || $a['status'] !== 'Open') redirectWithFlash('index.php', 'error', 'Assignment closed.');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model->submit($id, currentUserId(), trim($_POST['submission_text'] ?? ''));
        redirectWithFlash('index.php', 'success', 'Assignment submitted.');
    }
    $pageTitle = 'Submit Assignment';
    require __DIR__ . '/views/submit.php';
    exit;
}

if ($action === 'submissions' && isset($_GET['id']) && isTeacher()) {
    $pageTitle = 'Submissions';
    $assignment = $model->getById((int)$_GET['id']);
    $submissions = $model->submissionsForAssignment((int)$_GET['id']);
    require __DIR__ . '/views/submissions.php';
    exit;
}

if ($action === 'mark' && isset($_GET['sid']) && isTeacher()) {
    $sid = (int)$_GET['sid'];
    $sub = $model->getSubmission($sid);
    if (!$sub) redirectWithFlash('index.php', 'error', 'Not found.');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model->markSubmission($sid, (int)$_POST['marks'], trim($_POST['feedback'] ?? ''), $_POST['status'] ?? 'Marked');
        redirectWithFlash('index.php?action=submissions&id=' . (int)$sub['assignment_id'], 'success', 'Marked.');
    }
    $pageTitle = 'Mark Submission';
    require __DIR__ . '/views/mark.php';
    exit;
}

if ($action === 'search') {
    $q = trim($_GET['q'] ?? '');
    $pageTitle = 'Search Assignments';
    $items = $q ? $model->search($q) : [];
    require __DIR__ . '/views/search.php';
    exit;
}

if ($action === 'filter') {
    $pageTitle = 'Filter Assignments';
    $items = $model->listAll($_GET['subject'] ?? null, $_GET['status'] ?? null);
    require __DIR__ . '/views/filter.php';
    exit;
}

if ($action === 'create' && isTeacher()) {
    $pageTitle = 'Create Assignment';
    $row = ['title' => '', 'subject' => '', 'assign_date' => date('Y-m-d'), 'due_date' => date('Y-m-d'), 'status' => 'Open'];
    require __DIR__ . '/views/form.php';
    exit;
}

$pageTitle = 'Assignments';
$items = $model->listAll();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require __DIR__ . '/views/index.php';
