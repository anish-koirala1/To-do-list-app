<?php
/**
 * Assignment Model - assignments/model/Assignment.php
 *
 * Teacher-published assignments and student submissions (Sunil Kumar BK module).
 * Tables: assignments, assignment_submissions.
 */

class Assignment
{
    public function __construct(private PDO $pdo) {}

    /** List assignments with optional subject/status filters. */
    public function listAll(?string $subject = null, ?string $status = null): array
    {
        $sql = 'SELECT a.*, u.full_name AS teacher_name FROM assignments a
                JOIN users u ON a.teacher_id = u.id WHERE 1=1';
        $p = [];
        if ($subject) { $sql .= ' AND a.subject LIKE ?'; $p[] = "%$subject%"; }
        if ($status) { $sql .= ' AND a.status = ?'; $p[] = $status; }
        $sql .= ' ORDER BY a.due_date ASC';
        $st = $this->pdo->prepare($sql);
        $st->execute($p);
        return $st->fetchAll();
    }

    /** Search by title or subject (LIKE). */
    public function search(string $q): array
    {
        $st = $this->pdo->prepare(
            'SELECT a.*, u.full_name AS teacher_name FROM assignments a
             JOIN users u ON a.teacher_id = u.id
             WHERE a.title LIKE ? OR a.subject LIKE ? ORDER BY a.title'
        );
        $st->execute(["%$q%", "%$q%"]);
        return $st->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $st = $this->pdo->prepare(
            'SELECT a.*, u.full_name AS teacher_name FROM assignments a
             JOIN users u ON a.teacher_id = u.id WHERE a.id = ?'
        );
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function create(array $d): bool
    {
        return $this->pdo->prepare(
            'INSERT INTO assignments (teacher_id, title, subject, assign_date, due_date, status)
             VALUES (?,?,?,?,?,?)'
        )->execute([$d['teacher_id'], $d['title'], $d['subject'], $d['assign_date'], $d['due_date'], $d['status']]);
    }

    public function update(int $id, array $d): bool
    {
        return $this->pdo->prepare(
            'UPDATE assignments SET title=?, subject=?, assign_date=?, due_date=?, status=? WHERE id=?'
        )->execute([$d['title'], $d['subject'], $d['assign_date'], $d['due_date'], $d['status'], $id]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM assignments WHERE id = ?')->execute([$id]);
    }

    /** All student submissions for one assignment. */
    public function submissionsForAssignment(int $assignmentId): array
    {
        $st = $this->pdo->prepare(
            'SELECT s.*, u.full_name AS student_name FROM assignment_submissions s
             JOIN users u ON s.student_id = u.id WHERE s.assignment_id = ? ORDER BY s.submitted_at DESC'
        );
        $st->execute([$assignmentId]);
        return $st->fetchAll();
    }

    /**
     * Student submits text; upserts one row per (assignment, student).
     * Marks Late if submitted after due_date.
     */
    public function submit(int $assignmentId, int $studentId, string $text): bool
    {
        $late = $this->pdo->prepare('SELECT due_date, status FROM assignments WHERE id = ?');
        $late->execute([$assignmentId]);
        $a = $late->fetch();
        $status = 'Submitted';
        if ($a && strtotime(date('Y-m-d')) > strtotime($a['due_date'])) {
            $status = 'Late';
        }
        return $this->pdo->prepare(
            'INSERT INTO assignment_submissions (assignment_id, student_id, submission_text, status)
             VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE submission_text=VALUES(submission_text), status=VALUES(status), submitted_at=NOW()'
        )->execute([$assignmentId, $studentId, $text, $status]);
    }

    /** Teacher enters marks and feedback. */
    public function markSubmission(int $id, int $marks, string $feedback, string $status): bool
    {
        return $this->pdo->prepare(
            'UPDATE assignment_submissions SET marks=?, feedback=?, status=? WHERE id=?'
        )->execute([$marks, $feedback, $status, $id]);
    }

    public function getSubmission(int $id): ?array
    {
        $st = $this->pdo->prepare(
            'SELECT s.*, a.title AS assignment_title, u.full_name AS student_name
             FROM assignment_submissions s
             JOIN assignments a ON s.assignment_id = a.id
             JOIN users u ON s.student_id = u.id WHERE s.id = ?'
        );
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }
}
