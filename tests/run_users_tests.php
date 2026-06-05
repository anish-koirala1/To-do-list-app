<?php
/**
 * Users & Auth component tests — Anish Koirala
 * Tests: findUserForLogin, CRUD, List, Find, Filter, Validation, disabled account
 * Run: php tests/run_users_tests.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
require_once $root . '/config/database.php';
require_once $root . '/includes/ensure_users_schema.php';
require_once __DIR__ . '/lib/TestLogger.php';
require_once __DIR__ . '/lib/UserTestHelpers.php';

$fixtures = require __DIR__ . '/test_data.php';
$logDir = __DIR__ . '/logs';
$ts = getenv('APSU_TEST_TS') ?: date('Y-m-d_H-i-s');
if (!is_dir($logDir)) mkdir($logDir, 0775, true);

$pdo = getDB();
$logs = [];
$anyFail = false;

// --- Suite 1: Login (findUserForLogin) ---
$log = new TestLogger('users_login', 'findUserForLogin()', 'Anish Koirala', $logDir, $ts);
$log->info('Data types: VARCHAR username/email, bcrypt password, ENUM role, BOOLEAN is_active');

foreach ($fixtures['users']['valid_logins'] as $i => $acct) {
    $log->data("fixture[$i]", $acct);
    $user = findUserForLogin($pdo, $acct['login']);
    $ok = $user
        && password_verify($acct['password'], $user['password'])
        && $user['role'] === $acct['role']
        && (int)$user['is_active'] === 1;
    $log->assert('L' . ($i + 1), "Valid login via {$acct['type']}: {$acct['login']}", $ok);
}

foreach ($fixtures['users']['invalid_logins'] as $i => $bad) {
    $log->data("invalid[$i]", $bad);
    if ($bad['login'] === '') {
        $log->assert('LI' . ($i + 1), 'Empty login returns null', findUserForLogin($pdo, '') === null);
    } else {
        $user = findUserForLogin($pdo, $bad['login']);
        $pwOk = $user && password_verify($bad['password'], $user['password']);
        $log->assert('LI' . ($i + 1), "Rejected: {$bad['reason']}", !$pwOk);
    }
}

// Disabled user simulation (transaction)
$pdo->beginTransaction();
try {
    $tmpUser = 'disabled_' . time();
    $pdo->prepare(
        'INSERT INTO users (full_name, username, email, password, role, is_active) VALUES (?,?,?,?,?,0)'
    )->execute(['Disabled Test', $tmpUser, $tmpUser . '@test.local', password_hash('Test@1234', PASSWORD_BCRYPT), 'Student']);
    $disabled = findUserForLogin($pdo, $tmpUser);
    $log->assert('L10', 'Disabled account found but is_active=0', $disabled && (int)$disabled['is_active'] === 0);
} finally {
    $pdo->rollBack();
}

$logs[] = $log->write('TEST_users_login');
$anyFail = $anyFail || $log->failed();

// --- Suite 2: CRUD (users table) ---
$log = new TestLogger('users_crud', 'users table / PDO', 'Anish Koirala', $logDir, $ts);
$sample = $fixtures['users']['create_valid'];
$suffix = time();
$sample['username'] = 'crud.' . $suffix;
$sample['email'] = "crud.$suffix@uni.edu.dk";
$log->data('insert_payload', $sample);

$pdo->beginTransaction();
try {
    $hash = password_hash($sample['password'], PASSWORD_BCRYPT);
    $ins = $pdo->prepare(
        'INSERT INTO users (full_name, username, email, password, role, is_active) VALUES (?,?,?,?,?,?)'
    );
    $log->assert('C1', 'ADD — INSERT user', $ins->execute([
        $sample['full_name'], $sample['username'], $sample['email'], $hash, $sample['role'], $sample['is_active'],
    ]));
    $id = (int)$pdo->lastInsertId();
    $log->info("Inserted user id=$id (INT PK)");

    $upd = $pdo->prepare('UPDATE users SET full_name = ?, role = ? WHERE id = ?');
    $log->assert('C2', 'EDIT — UPDATE full_name and ENUM role', $upd->execute(['Updated Name', 'Student', $id]));

    $sel = $pdo->prepare('SELECT full_name, role, is_active FROM users WHERE id = ?');
    $sel->execute([$id]);
    $row = $sel->fetch();
    $log->assert('C3', 'LIST/READ — SELECT returns updated row', $row && $row['full_name'] === 'Updated Name');

    $toggle = $pdo->prepare('UPDATE users SET is_active = 0 WHERE id = ?');
    $log->assert('C4', 'EDIT — toggle is_active BOOLEAN', $toggle->execute([$id]));

    $del = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $log->assert('C5', 'DELETE — remove user', $del->execute([$id]));
    $chk = $pdo->prepare('SELECT id FROM users WHERE id = ?');
    $chk->execute([$id]);
    $log->assert('C6', 'DELETE — row no longer exists', $chk->fetch() === false);
} finally {
    $pdo->rollBack();
}

$logs[] = $log->write('TEST_users_crud');
$anyFail = $anyFail || $log->failed();

// --- Suite 3: Find & Filter (list_users pattern) ---
$log = new TestLogger('users_find_filter', 'userListQuery()', 'Anish Koirala', $logDir, $ts);

foreach ($fixtures['users']['search_terms'] as $i => $case) {
    $log->data("search[$i]", $case);
    $rows = userListQuery($pdo, $case['q'], null, null);
    $log->assert('F' . ($i + 1), "FIND — '{$case['q']}' ({$case['type']}) count>={$case['expect_min']}", count($rows) >= $case['expect_min']);
}

foreach ($fixtures['users']['filter_cases'] as $i => $case) {
    $log->data("filter[$i]", $case);
    $rows = userListQuery($pdo, null, $case['role'] ?: null, $case['status']);
    $log->assert('FL' . ($i + 1), "FILTER — {$case['label']}", is_array($rows));
    if ($case['role'] !== '') {
        $allRole = array_filter($rows, fn($r) => $r['role'] === $case['role']);
        $log->assert('FL' . ($i + 1) . 'b', "FILTER — all rows match role {$case['role']}", count($allRole) === count($rows));
    }
}

$logs[] = $log->write('TEST_users_find_filter');
$anyFail = $anyFail || $log->failed();

// --- Suite 4: Validation ---
$log = new TestLogger('users_validation', 'validateUserCreateData()', 'Anish Koirala', $logDir, $ts);
$valid = validateUserCreateData(array_merge($fixtures['users']['create_valid'], [
    'username' => 'valid.' . time(),
    'email' => 'valid.' . time() . '@uni.edu.dk',
    'confirm' => $fixtures['users']['create_valid']['password'],
]), $pdo);
$log->data('valid_fixture', $fixtures['users']['create_valid']);
$log->assert('V1', 'Valid unicode name + email accepted', empty($valid['errors']));

$boundary = validateUserCreateData(array_merge($fixtures['users']['create_boundary'], [
    'username' => 'bnd' . time(),
    'email' => 'bnd' . time() . '@b.co',
    'confirm' => $fixtures['users']['create_boundary']['password'],
]), $pdo);
$log->assert('V2', 'Boundary 100-char name accepted', empty($boundary['errors']));

foreach ($fixtures['users']['create_invalid'] as $i => $case) {
    $payload = array_merge($fixtures['users']['create_valid'], [
        'username' => 'inv' . $i . time(),
        'email' => "inv$i." . time() . '@uni.edu.dk',
        'confirm' => 'Abcd1234',
        $case['field'] => $case['value'],
    ]);
    if ($case['field'] === 'password') {
        $payload['confirm'] = $case['value'];
    }
    $result = validateUserCreateData($payload, $pdo);
    $log->data("invalid[$i]", $case);
    $log->assert('VI' . ($i + 1), "Reject {$case['field']}: {$case['expect']}", !empty($result['errors'][$case['field']]));
}

$dup = validateUserCreateData([
    'full_name' => 'Dup Test', 'username' => 'admin', 'email' => 'new@x.com',
    'password' => 'Abcd1234', 'confirm' => 'Abcd1234', 'role' => 'Student',
], $pdo);
$log->assert('V10', 'Duplicate username rejected', isset($dup['errors']['username']));

$logs[] = $log->write('TEST_users_validation');
$anyFail = $anyFail || $log->failed();

echo "\nUsers tests complete. Logs:\n" . implode("\n", $logs) . "\n";
exit($anyFail ? 1 : 0);
