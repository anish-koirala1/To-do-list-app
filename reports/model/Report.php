<?php

class Report
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(?string $search = null, ?string $status = null, ?string $priority = null, ?int $userId = null): array
    {
        $sql = 'SELECT r.*, u.full_name AS owner_name FROM reports r JOIN users u ON r.user_id = u.id WHERE 1=1';
        $params = [];
        if ($userId !== null) {
            $sql .= ' AND r.user_id = ?';
            $params[] = $userId;
        }
        if ($search) {
            $sql .= ' AND (r.title LIKE ? OR r.subject LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($status) {
            $sql .= ' AND r.status = ?';
            $params[] = $status;
        }
        if ($priority) {
            $sql .= ' AND r.priority = ?';
            $params[] = $priority;
        }
        $sql .= ' ORDER BY r.due_date ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT r.*, u.full_name AS owner_name FROM reports r JOIN users u ON r.user_id = u.id WHERE r.id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reports (user_id, title, subject, priority, assign_date, due_date, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute([
            $data['user_id'], $data['title'], $data['subject'], $data['priority'],
            $data['assign_date'], $data['due_date'], $data['status']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE reports SET title=?, subject=?, priority=?, assign_date=?, due_date=?, status=? WHERE id=?'
        );
        return $stmt->execute([
            $data['title'], $data['subject'], $data['priority'],
            $data['assign_date'], $data['due_date'], $data['status'], $id
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM reports WHERE id = ?')->execute([$id]);
    }

    public function progressStats(): array
    {
        return $this->pdo->query("SELECT
            COUNT(*) AS total,
            SUM(status = 'Pending') AS pending,
            SUM(status = 'In Progress') AS in_progress,
            SUM(status = 'Completed') AS completed
            FROM reports")->fetch();
    }
}
