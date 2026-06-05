<?php
/**
 * Shared test logger — writes timestamped log files under tests/logs/.
 */
declare(strict_types=1);

class TestLogger
{
    private array $lines = [];
    private int $pass = 0;
    private int $fail = 0;

    public function __construct(
        private string $suite,
        private string $classOrModule,
        private string $owner,
        private string $logDir,
        private string $timestamp
    ) {}

    public function info(string $message): void
    {
        $this->line('INFO', $message);
    }

    public function data(string $label, mixed $value): void
    {
        $display = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string)$value;
        $this->line('DATA', "$label => $display");
    }

    public function assert(string $testId, string $label, bool $ok): void
    {
        $this->line($ok ? 'PASS' : 'FAIL', "[$testId] $label");
        $ok ? $this->pass++ : $this->fail++;
    }

    public function line(string $status, string $message): void
    {
        $entry = sprintf('[%s] %-4s %s', date('H:i:s'), $status, $message);
        $this->lines[] = $entry;
        echo "[$this->suite] $entry\n";
    }

    public function write(string $filenameStem): string
    {
        $path = $this->logDir . '/' . $filenameStem . '_' . $this->timestamp . '.log';
        $header = implode(PHP_EOL, [
            'APSU Test Log',
            'Suite  : ' . $this->suite,
            'Class  : ' . $this->classOrModule,
            'Owner  : ' . $this->owner,
            'Run at : ' . date('c'),
            'PASS   : ' . $this->pass,
            'FAIL   : ' . $this->fail,
            str_repeat('=', 70),
            '',
        ]);
        file_put_contents($path, $header . implode(PHP_EOL, $this->lines) . PHP_EOL);
        return $path;
    }

    public function passCount(): int { return $this->pass; }
    public function failCount(): int { return $this->fail; }
    public function failed(): bool { return $this->fail > 0; }
}
