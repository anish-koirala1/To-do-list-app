<?php

class ScheduledClass
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(?int $userId = null): array
    {
        $sql = 'SELECT sc.*, r.title AS report_title, u.full_name AS user_name
                FROM scheduled_classes sc
                LEFT JOIN reports r ON sc.report_id = r.id
                JOIN users u ON sc.user_id = u.id';
        $params = [];
        if ($userId) {
            $sql .= ' WHERE sc.user_id = ?';
            $params[] = $userId;
        }
        $sql .= ' ORDER BY sc.start_time ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM scheduled_classes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO scheduled_classes (report_id, user_id, title, instructor, classroom, start_time, end_time)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute([
            $d['report_id'] ?: null, $d['user_id'], $d['title'], $d['instructor'],
            $d['classroom'], $d['start_time'], $d['end_time']
        ]);
    }

    public function update(int $id, array $d): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE scheduled_classes SET report_id=?, title=?, instructor=?, classroom=?, start_time=?, end_time=? WHERE id=?'
        );
        return $stmt->execute([
            $d['report_id'] ?: null, $d['title'], $d['instructor'], $d['classroom'],
            $d['start_time'], $d['end_time'], $id
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM scheduled_classes WHERE id = ?')->execute([$id]);
    }

    public function reportOptions(): array
    {
        return $this->pdo->query('SELECT id, title FROM reports ORDER BY title')->fetchAll();
    }
}
