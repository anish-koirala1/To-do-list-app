<?php

class Assignment
{
    private $conn;
    private $table = "assignments";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($task_name, $assigned_to, $due_date, $status)
    {
        $query = "
            INSERT INTO {$this->table}
            (task_name, assigned_to, due_date, status)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $task_name,
            $assigned_to,
            $due_date,
            $status
        ]);
    }

    public function update($id, $task_name, $assigned_to, $due_date, $status)
    {
        $query = "
            UPDATE {$this->table}
            SET task_name = ?, assigned_to = ?, due_date = ?, status = ?
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $task_name,
            $assigned_to,
            $due_date,
            $status,
            $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function searchById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filterAssignments($status, $from_date, $to_date)
    {
        $query = "SELECT * FROM assignments WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        if (!empty($from_date)) {
            $query .= " AND due_date >= ?";
            $params[] = $from_date;
        }

        if (!empty($to_date)) {
            $query .= " AND due_date <= ?";
            $params[] = $to_date;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
