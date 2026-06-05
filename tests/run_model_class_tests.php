<?php
/**
 * Per-class model tests — one log file per PHP model class.
 * Run: php tests/run_model_class_tests.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
require_once $root . '/config/database.php';
require_once $root . '/includes/ensure_users_schema.php';
require_once $root . '/includes/validate_academics.php';
require_once $root . '/reports/model/Report.php';
require_once $root . '/classes/model/ScheduledClass.php';
require_once $root . '/exams/model/Exam.php';
require_once $root . '/assignments/model/Assignment.php';
require_once __DIR__ . '/lib/TestLogger.php';

$fixtures = require __DIR__ . '/test_data.php';
$logDir = __DIR__ . '/logs';
$ts = getenv('APSU_TEST_TS') ?: date('Y-m-d_H-i-s');
if (!is_dir($logDir)) mkdir($logDir, 0775, true);

$pdo = getDB();
$written = [];
$anyFail = false;

// --- Report class ---
$log = new TestLogger('class_Report', 'Report', 'Utsav Luitel', $logDir, $ts);
$model = new Report($pdo);
$log->data('fixture_valid', $fixtures['reports']['valid']);
$all = $model->getAll();
$log->assert('R1', 'getAll() returns array', is_array($all));
$log->assert('R2', 'getAll() has seeded rows', count($all) > 0);

if ($all) {
    $id = (int)$all[0]['id'];
    $one = $model->getById($id);
    $log->assert('R3', 'getById() returns owner_name join', $one && isset($one['owner_name']));
}

foreach ($fixtures['reports']['filter_status'] as $status) {
    $filtered = $model->getAll(null, $status, null);
    $log->assert('R4', "getAll(status=$status)", is_array($filtered));
}

foreach ($fixtures['reports']['filter_priority'] as $pri) {
    $filtered = $model->getAll(null, null, $pri);
    $log->assert('R5', "getAll(priority=$pri)", is_array($filtered));
}

$stats = $model->progressStats();
$log->assert('R6', 'progressStats() keys', isset($stats['total'], $stats['pending'], $stats['completed']));

$pdo->beginTransaction();
try {
    $teacher = findUserForLogin($pdo, 'teacher');
    $payload = array_merge($fixtures['reports']['valid'], [
        'user_id' => (int)$teacher['user_id'],
        'title' => 'Test Report ' . time(),
    ]);
    $log->assert('R7', 'create() INSERT', $model->create($payload));
    $newId = (int)$pdo->lastInsertId();
    $log->assert('R8', 'update() DATE/ENUM fields', $model->update($newId, array_merge($payload, ['status' => 'Completed'])));
    $log->assert('R9', 'delete() removes row', $model->delete($newId));
} finally {
    $pdo->rollBack();
}

$written[] = $log->write('TEST_class_Report');
$anyFail = $anyFail || $log->failed();

// --- ScheduledClass ---
$log = new TestLogger('class_ScheduledClass', 'ScheduledClass', 'Puskar Bastola', $logDir, $ts);
$cm = new ScheduledClass($pdo);
$log->data('fixture_valid', $fixtures['classes']['valid']);
$classes = $cm->getAll();
$log->assert('SC1', 'getAll()', count($classes) > 0);
$log->assert('SC2', 'reportOptions()', count($cm->reportOptions()) > 0);

if ($classes) {
    $log->assert('SC3', 'getById()', $cm->getById((int)$classes[0]['id']) !== null);
}

$bad = validateClassData(array_merge($fixtures['classes']['valid'], $fixtures['classes']['invalid']['end_before_start']), $pdo);
$log->assert('SC4', 'validate end before start', isset($bad['errors']['end_time']));

$pdo->beginTransaction();
try {
    $student = findUserForLogin($pdo, 'student');
    $d = validateClassData(array_merge($fixtures['classes']['valid'], [
        'title' => 'Test Class ' . time(),
    ]), $pdo)['data'];
    $log->assert('SC5', 'create()', $cm->create([
        'report_id' => null, 'user_id' => (int)$student['user_id'],
        'title' => $d['title'], 'instructor' => $d['instructor'],
        'classroom' => $d['classroom'], 'start_time' => $d['start_time'], 'end_time' => $d['end_time'],
    ]));
    $cid = (int)$pdo->lastInsertId();
    $log->assert('SC6', 'update()', $cm->update($cid, array_merge($d, ['title' => 'Updated Class'])));
    $log->assert('SC7', 'delete()', $cm->delete($cid));
} finally {
    $pdo->rollBack();
}

$written[] = $log->write('TEST_class_ScheduledClass');
$anyFail = $anyFail || $log->failed();

// --- Exam ---
$log = new TestLogger('class_Exam', 'Exam', 'Puskar Bastola', $logDir, $ts);
$em = new Exam($pdo);
$exams = $em->getAll();
$log->assert('E1', 'getAll()', count($exams) > 0);
$log->data('duration_samples', $fixtures['exams']['duration_minutes']);

if ($exams) {
    $eid = (int)$exams[0]['id'];
    $qs = $em->getQuestions($eid);
    $log->assert('E2', 'getQuestions()', count($qs) > 0);
    $log->assert('E3', 'classOptions()', count($em->classOptions()) > 0);

    $student = findUserForLogin($pdo, 'student');
    $pdo->beginTransaction();
    try {
        $attemptId = $em->startAttempt($eid, (int)$student['user_id']);
        $log->assert('E4', 'startAttempt() INT id', $attemptId > 0);
        $answers = [];
        foreach ($qs as $q) {
            $answers[(int)$q['id']] = $q['correct_option'];
        }
        $em->submitAttempt($attemptId, $answers);
        $att = $em->getAttempts($eid, (int)$student['user_id']);
        $log->assert('E5', 'submitAttempt() scores', ($att[0]['score'] ?? -1) === ($att[0]['total_questions'] ?? 0));
    } finally {
        $pdo->rollBack();
    }
}

$pdo->beginTransaction();
try {
    $teacher = findUserForLogin($pdo, 'teacher');
    $newId = $em->create([
        'scheduled_class_id' => null, 'user_id' => (int)$teacher['user_id'],
        'title' => 'PHPUnit Style Exam', 'subject' => 'Testing', 'duration_minutes' => 45,
    ]);
    $log->assert('E6', 'create() returns id', $newId > 0);
    $mcq = $fixtures['exams']['mcq_sample'];
    $log->assert('E7', 'addQuestion()', $em->addQuestion(array_merge($mcq, [
        'exam_id' => $newId, 'user_id' => (int)$teacher['user_id'],
    ])));
    $log->assert('E8', 'delete()', $em->delete($newId));
} finally {
    $pdo->rollBack();
}

$written[] = $log->write('TEST_class_Exam');
$anyFail = $anyFail || $log->failed();

// --- Assignment ---
$log = new TestLogger('class_Assignment', 'Assignment', 'Sunil Kumar BK', $logDir, $ts);
$am = new Assignment($pdo);
$log->data('fixture_valid', $fixtures['assignments']['valid']);
$list = $am->listAll();
$log->assert('A1', 'listAll()', count($list) > 0);

$search = $am->search($fixtures['assignments']['search_term']);
$log->assert('A2', 'search() VARCHAR', count($search) > 0);

foreach ($fixtures['assignments']['status_filter'] as $st) {
    $log->assert('A3', "listAll(status=$st)", is_array($am->listAll(null, $st)));
}

if ($list) {
    $aid = (int)$list[0]['id'];
    $log->assert('A4', 'getById() teacher_name', ($am->getById($aid)['teacher_name'] ?? '') !== '');
    $log->assert('A5', 'submissionsForAssignment()', is_array($am->submissionsForAssignment($aid)));
}

$pdo->beginTransaction();
try {
    $teacher = findUserForLogin($pdo, 'teacher');
    $student = findUserForLogin($pdo, 'student');
    $payload = array_merge($fixtures['assignments']['valid'], [
        'teacher_id' => (int)$teacher['user_id'],
        'title' => 'Test Assignment ' . time(),
    ]);
    $log->assert('A6', 'create()', $am->create($payload));
    $newAid = (int)$pdo->lastInsertId();
    $log->assert('A7', 'submit() TEXT', $am->submit($newAid, (int)$student['user_id'], $fixtures['assignments']['submission_text']));
    $subs = $am->submissionsForAssignment($newAid);
    if ($subs) {
        $sid = (int)$subs[0]['id'];
        $log->assert('A8', 'markSubmission() INT marks', $am->markSubmission($sid, 88, 'Well done', 'Marked'));
    }
    $log->assert('A9', 'delete()', $am->delete($newAid));
} finally {
    $pdo->rollBack();
}

$written[] = $log->write('TEST_class_Assignment');
$anyFail = $anyFail || $log->failed();

echo "\nModel class tests complete:\n" . implode("\n", $written) . "\n";
exit($anyFail ? 1 : 0);
