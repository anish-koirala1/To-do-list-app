<?php
/**
 * APSU test runner — single entry point for all tests and logs.
 * Clears previous logs, runs suites, writes TESTING_REPORT.md.
 *
 * Run: php tests/run_all_tests.php
 */
declare(strict_types=1);

$testDir = __DIR__;
$logDir = $testDir . '/logs';
$ts = date('Y-m-d_H-i-s');
$php = PHP_BINARY;
putenv('APSU_TEST_TS=' . $ts);

if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
}

// Remove previous log files — only the latest run is kept
foreach (glob($logDir . '/*.log') ?: [] as $oldLog) {
    @unlink($oldLog);
}

$suites = [
    ['name' => 'Users & Auth (component)', 'script' => 'run_users_tests.php', 'owner' => 'Anish Koirala'],
    ['name' => 'Model classes (per-class)', 'script' => 'run_model_class_tests.php', 'owner' => 'Team'],
];

$results = [];
$masterLog = $logDir . "/TEST_RUN_MASTER_{$ts}.log";
$masterLines = [
    'APSU Test Run',
    'Started: ' . date('c'),
    'Run id : ' . $ts,
    str_repeat('=', 70),
    '',
];

foreach ($suites as $suite) {
    $script = $testDir . '/' . $suite['script'];
    $cmd = escapeshellarg($php) . ' ' . escapeshellarg($script) . ' 2>&1';
    exec($cmd, $output, $code);
    $text = implode("\n", $output);
    $results[] = [
        'name'   => $suite['name'],
        'owner'  => $suite['owner'],
        'script' => $suite['script'],
        'exit'   => $code,
        'output' => $text,
    ];
    $masterLines[] = sprintf('[%s] %s — exit %d', $code === 0 ? 'PASS' : 'FAIL', $suite['name'], $code);
    $masterLines[] = $text;
    $masterLines[] = str_repeat('-', 70);
    echo "\n=== {$suite['name']} (exit $code) ===\n$text\n";
}

file_put_contents($masterLog, implode(PHP_EOL, $masterLines) . PHP_EOL);

$logFiles = array_values(array_filter(scandir($logDir) ?: [], fn($f) => str_ends_with($f, '.log')));
sort($logFiles);

$totalPass = count(array_filter($results, fn($r) => $r['exit'] === 0));
$totalFail = count($results) - $totalPass;
$overall = $totalFail === 0 ? 'PASS' : 'FAIL';

$report = "# APSU Testing Report\n\n";
$report .= "**Generated:** " . date('Y-m-d H:i:s') . "  \n";
$report .= "**Run id:** `$ts`  \n";
$report .= "**Master log:** `logs/" . basename($masterLog) . "`  \n";
$report .= "**Overall:** **$overall** ($totalPass/" . count($results) . " suites passed)\n\n";
$report .= "## Rubric alignment (Testing 10%)\n\n";
$report .= "| Requirement | Evidence |\n|-------------|----------|\n";
$report .= "| Test plan | `tests/TEST_PLAN.md` |\n";
$report .= "| Test data | `tests/test_data.php` |\n";
$report .= "| Component tests | `run_users_tests.php` → 4 log files |\n";
$report .= "| Class tests | `run_model_class_tests.php` → 4 model log files |\n";
$report .= "| Complete logs | `tests/logs/` (this run only) |\n\n";
$report .= "## Suite results\n\n";
$report .= "| Suite | Owner | Script | Result |\n|-------|-------|--------|--------|\n";
foreach ($results as $r) {
    $report .= sprintf(
        "| %s | %s | `%s` | **%s** (exit %d) |\n",
        $r['name'],
        $r['owner'],
        $r['script'],
        $r['exit'] === 0 ? 'PASS' : 'FAIL',
        $r['exit']
    );
}
$report .= "\n## Log files\n\n";
foreach ($logFiles as $f) {
    $report .= "- `logs/$f`\n";
}
$report .= "\n## Reproduce\n\n```powershell\ncd app\nphp tests/run_all_tests.php\n```\n";

file_put_contents($testDir . '/TESTING_REPORT.md', $report);

echo "\nMaster log: $masterLog\n";
echo "Report:     $testDir/TESTING_REPORT.md\n";
echo "Log count:  " . count($logFiles) . "\n";
echo "Overall:    $overall\n";
exit($totalFail > 0 ? 1 : 0);
