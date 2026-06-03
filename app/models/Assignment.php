<?php

class Assignment
{
    private $conn;

    private $table = "assignments";

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Assignments
    |--------------------------------------------------------------------------
    */
    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Assignment By ID
    |--------------------------------------------------------------------------
    */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Assignment
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Update Assignment
    |--------------------------------------------------------------------------
    */
    public function update($id, $task_name, $assigned_to, $due_date, $status)
    {
        $query = "
            UPDATE {$this->table}
            SET
                task_name = ?,
                assigned_to = ?,
                due_date = ?,
                status = ?
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

    /*
    |--------------------------------------------------------------------------
    | Delete Assignment
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Assignment By ID
    |--------------------------------------------------------------------------
    */
    public function searchById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Assignment By Status
    |--------------------------------------------------------------------------
    */
    public function filterAssignments($status, $from_date, $to_date)
{
    $query = "SELECT * FROM assignments WHERE 1=1";

    /*
    |------------------------------------------------------------
    | FILTER BY STATUS
    |------------------------------------------------------------
    */
    if (!empty($status)) {

        $query .= " AND status = :status";
    }

    /*
    |------------------------------------------------------------
    | FILTER BY FROM DATE
    |------------------------------------------------------------
    */
    if (!empty($from_date)) {

        $query .= " AND due_date >= :from_date";
    }

    /*
    |------------------------------------------------------------
    | FILTER BY TO DATE
    |------------------------------------------------------------
    */
    if (!empty($to_date)) {

        $query .= " AND due_date <= :to_date";
    }

    /*
    |------------------------------------------------------------
    | PREPARE QUERY
    |------------------------------------------------------------
    */
    $stmt = $this->conn->prepare($query);

    /*
    |------------------------------------------------------------
    | BIND STATUS
    |------------------------------------------------------------
    */
    if (!empty($status)) {

        $stmt->bindParam(':status', $status);
    }

    /*
    |------------------------------------------------------------
    | BIND FROM DATE
    |------------------------------------------------------------
    */
    if (!empty($from_date)) {

        $stmt->bindParam(':from_date', $from_date);
    }

    /*
    |------------------------------------------------------------
    | BIND TO DATE
    |------------------------------------------------------------
    */
    if (!empty($to_date)) {

        $stmt->bindParam(':to_date', $to_date);
    }

    /*
    |------------------------------------------------------------
    | EXECUTE QUERY
    |------------------------------------------------------------
    */
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}

?>