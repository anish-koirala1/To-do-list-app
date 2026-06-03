<?php

class Task
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll($search, $priority, $status, $date, $limit, $offset)
    {
        $sql = "SELECT * FROM tasks WHERE 1=1";
        $params = [];
        $paramIndex = 1;

        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR id = ?)";
            $params[] = "%$search%";
            $params[] = is_numeric($search) ? $search : 0;
            $paramIndex += 2;
        }

        if (!empty($priority)) {
            $sql .= " AND priority = ?";
            $params[] = $priority;
            $paramIndex++;
        }

        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
            $paramIndex++;
        }

        if (!empty($date)) {
            $sql .= " AND due_date = ?";
            $params[] = $date;
            $paramIndex++;
        }

        $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";

        try {
            $stmt = $this->pdo->prepare($sql);
            
            // Bind all filter parameters
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }
            
            // Bind LIMIT and OFFSET with proper type casting
            $stmt->bindValue($paramIndex, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue($paramIndex + 1, (int)$offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error in getAll: ' . $e->getMessage());
            return [];
        }
    }

    public function create($title, $priority, $status, $dueDate)
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO tasks (title, priority, status, due_date)
                 VALUES (?, ?, ?, ?)"
            );

            return $stmt->execute([
                $title,
                $priority,
                $status,
                $dueDate
            ]);
        } catch (PDOException $e) {
            error_log('Database error in create: ' . $e->getMessage());
            return false;
        }
    }

    public function getTotal($search, $priority, $status, $date)
    {
        $sql = "SELECT COUNT(*) as total FROM tasks WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR id = ?)";
            $params[] = "%$search%";
            $params[] = is_numeric($search) ? $search : 0;
        }

        if (!empty($priority)) {
            $sql .= " AND priority = ?";
            $params[] = $priority;
        }

        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        if (!empty($date)) {
            $sql .= " AND due_date = ?";
            $params[] = $date;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log('Database error in getTotal: ' . $e->getMessage());
            return 0;
        }
    }
}
