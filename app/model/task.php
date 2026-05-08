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

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR id = :id)";
            $params[':search'] = "%$search%";
            $params[':id'] = is_numeric($search) ? $search : 0;
        }

        if (!empty($priority)) {
            $sql .= " AND priority = :priority";
            $params[':priority'] = $priority;
        }

        if (!empty($status)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        if (!empty($date)) {
            $sql .= " AND due_date = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($title, $priority, $status, $dueDate)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tasks (title, priority, status, due_date)
             VALUES (:title, :priority, :status, :due_date)"
        );

        return $stmt->execute([
            ':title' => $title,
            ':priority' => $priority,
            ':status' => $status,
            ':due_date' => $dueDate
        ]);
    }
}