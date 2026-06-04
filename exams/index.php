<?php
/**
 * Exams Controller - exams/index.php
 *
 * Routes: index, create, delete, questions, add_question, take (student), attempts.
 * Optional ?class_id= filters exams for one scheduled class.
 */
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/model/Exam.php';

$pdo = getDB();
$exam = new Exam($pdo);
$action = $_GET['action'] ?? 'index';
$classId = isset($_GET['class_id']) ? (int)$_GET['class_id'] : null;

if ($action === 'delete' && isset($_GET['id'])) {
    requireStaff();
    $exam->delete((int)$_GET['id']);
    redirectWithFlash('index.php' . ($classId ? "?class_id=$classId" : ''), 'success', 'Exam deleted.');
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireStaff();
    $exam->create([
        'scheduled_class_id' => (int)($_POST['scheduled_class_id'] ?? 0) ?: null,
        'user_id' => currentUserId(),
        'title' => trim($_POST['title'] ?? ''),
        'subject' => trim($_POST['subject'] ?? ''),
        'duration_minutes' => (int)($_POST['duration_minutes'] ?? 60),
    ]);
    redirectWithFlash('index.php', 'success', 'Exam created.');
}

if ($action === 'add_question' && isset($_GET['exam_id'])) {
    requireStaff();
    $eid = (int)$_GET['exam_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $exam->addQuestion([
            'exam_id' => $eid, 'user_id' => currentUserId(),
            'question_text' => trim($_POST['question_text'] ?? ''),
            'option_a' => $_POST['option_a'] ?? '', 'option_b' => $_POST['option_b'] ?? '',
            'option_c' => $_POST['option_c'] ?? '', 'option_d' => $_POST['option_d'] ?? '',
            'correct_option' => $_POST['correct_option'] ?? 'A',
        ]);
        redirectWithFlash("index.php?action=questions&exam_id=$eid", 'success', 'Question added.');
    }
    $pageTitle = 'Add Question';
    require __DIR__ . '/views/question_form.php';
    exit;
}

if ($action === 'questions' && isset($_GET['exam_id'])) {
    requireStaff();
    $eid = (int)$_GET['exam_id'];
    $pageTitle = 'Exam Questions';
    $questions = $exam->getQuestions($eid);
    $ex = $exam->getById($eid);
    require __DIR__ . '/views/questions.php';
    exit;
}

// Student takes exam: start attempt on GET, grade on POST
if ($action === 'take' && isset($_GET['exam_id'])) {
    if (!isStudent()) {
        redirectWithFlash('index.php', 'error', 'Only students can take exams.');
    }
    $eid = (int)$_GET['exam_id'];
    $questions = $exam->getQuestions($eid);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aid = (int)($_POST['attempt_id'] ?? 0);
        $exam->submitAttempt($aid, $_POST['answer'] ?? []);
        redirectWithFlash('index.php?action=attempts', 'success', 'Exam submitted.');
    }
    $attemptId = $exam->startAttempt($eid, currentUserId());
    $pageTitle = 'Take Exam';
    $ex = $exam->getById($eid);
    require __DIR__ . '/views/take.php';
    exit;
}

if ($action === 'attempts') {
    $pageTitle = 'Exam Attempts';
    $uid = isStudent() ? currentUserId() : null;
    $attempts = $exam->getAttempts(null, $uid);
    require __DIR__ . '/views/attempts.php';
    exit;
}

if ($action === 'create') {
    requireStaff();
    $pageTitle = 'Create Exam';
    $classes = $exam->classOptions();
    require __DIR__ . '/views/form.php';
    exit;
}

$pageTitle = 'Exams';
$list = $exam->getAll($classId, isStudent() ? currentUserId() : null);
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require __DIR__ . '/views/index.php';
