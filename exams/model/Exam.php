<?php
/**
 * Exam module — Puskar Bastola
 * Exam, ExamQuestion, ExamAttempt, ExamAttemptAnswer
 */

class Exam
{
    public function __construct(private PDO $pdo) {}

    public function getAll(?int $classId = null, ?int $userId = null): array
    {
        $sql = 'SELECT e.*, sc.title AS class_title FROM exams e
                LEFT JOIN scheduled_classes sc ON e.scheduled_class_id = sc.id WHERE 1=1';
        $p = [];
        if ($classId) { $sql .= ' AND e.scheduled_class_id = ?'; $p[] = $classId; }
        if ($userId) { $sql .= ' AND e.user_id = ?'; $p[] = $userId; }
        $sql .= ' ORDER BY e.created_at DESC';
        $st = $this->pdo->prepare($sql);
        $st->execute($p);
        return $st->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $st = $this->pdo->prepare('SELECT * FROM exams WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function create(array $d): int
    {
        $st = $this->pdo->prepare(
            'INSERT INTO exams (scheduled_class_id, user_id, title, subject, duration_minutes) VALUES (?,?,?,?,?)'
        );
        $st->execute([$d['scheduled_class_id'] ?: null, $d['user_id'], $d['title'], $d['subject'], $d['duration_minutes']]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): bool
    {
        return $this->pdo->prepare(
            'UPDATE exams SET scheduled_class_id=?, title=?, subject=?, duration_minutes=? WHERE id=?'
        )->execute([$d['scheduled_class_id'] ?: null, $d['title'], $d['subject'], $d['duration_minutes'], $id]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM exams WHERE id = ?')->execute([$id]);
    }

    public function getQuestions(int $examId): array
    {
        $st = $this->pdo->prepare('SELECT * FROM exam_questions WHERE exam_id = ? ORDER BY id');
        $st->execute([$examId]);
        return $st->fetchAll();
    }

    public function addQuestion(array $d): bool
    {
        return $this->pdo->prepare(
            'INSERT INTO exam_questions (exam_id, user_id, question_text, option_a, option_b, option_c, option_d, correct_option)
             VALUES (?,?,?,?,?,?,?,?)'
        )->execute([
            $d['exam_id'], $d['user_id'], $d['question_text'], $d['option_a'], $d['option_b'],
            $d['option_c'], $d['option_d'], strtoupper($d['correct_option'])
        ]);
    }

    public function startAttempt(int $examId, int $userId): int
    {
        $cnt = $this->pdo->prepare('SELECT COUNT(*) FROM exam_questions WHERE exam_id = ?');
        $cnt->execute([$examId]);
        $total = (int)$cnt->fetchColumn();
        $this->pdo->prepare(
            'INSERT INTO exam_attempts (user_id, exam_id, score, total_questions, started_at) VALUES (?,?,0,?,NOW())'
        )->execute([$userId, $examId, $total]);
        return (int)$this->pdo->lastInsertId();
    }

    public function submitAttempt(int $attemptId, array $answers): void
    {
        $attempt = $this->pdo->prepare('SELECT * FROM exam_attempts WHERE id = ?');
        $attempt->execute([$attemptId]);
        $att = $attempt->fetch();
        if (!$att) return;

        $score = 0;
        foreach ($answers as $qid => $opt) {
            $q = $this->pdo->prepare('SELECT correct_option FROM exam_questions WHERE id = ?');
            $q->execute([(int)$qid]);
            $correct = $q->fetchColumn();
            $sel = strtoupper((string)$opt);
            if ($correct && $sel === $correct) $score++;
            $this->pdo->prepare(
                'INSERT INTO exam_attempt_answers (attempt_id, question_id, selected_option) VALUES (?,?,?)'
            )->execute([$attemptId, (int)$qid, $sel]);
        }
        $this->pdo->prepare(
            'UPDATE exam_attempts SET score=?, completed_at=NOW() WHERE id=?'
        )->execute([$score, $attemptId]);
    }

    public function getAttempts(?int $examId = null, ?int $userId = null): array
    {
        $sql = 'SELECT a.*, e.title AS exam_title, u.full_name FROM exam_attempts a
                JOIN exams e ON a.exam_id = e.id JOIN users u ON a.user_id = u.id WHERE 1=1';
        $p = [];
        if ($examId) { $sql .= ' AND a.exam_id = ?'; $p[] = $examId; }
        if ($userId) { $sql .= ' AND a.user_id = ?'; $p[] = $userId; }
        $sql .= ' ORDER BY a.started_at DESC';
        $st = $this->pdo->prepare($sql);
        $st->execute($p);
        return $st->fetchAll();
    }

    public function classOptions(): array
    {
        return $this->pdo->query('SELECT id, title FROM scheduled_classes ORDER BY title')->fetchAll();
    }
}
