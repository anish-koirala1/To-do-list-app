<?php

require_once "../model/task.php";

class TaskController
{
    private $taskModel;

    public function __construct($pdo)
    {
        $this->taskModel = new Task($pdo);
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $priority = $_GET['priority'] ?? '';
        $status = $_GET['status'] ?? '';
        $date = $_GET['date'] ?? '';

        $page = $_GET['page'] ?? 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $tasks = $this->taskModel->getAll(
            $search,
            $priority,
            $status,
            $date,
            $limit,
            $offset
        );

        require "../views/tasks/index.php";
    }

    public function create()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $priority = $_POST['priority'];
            $status = $_POST['status'];
            $dueDate = $_POST['due_date'];

            if (empty($title)) {
                $errors[] = "Title cannot be empty.";
            }

            if (empty($dueDate)) {
                $errors[] = "Due date is required.";
            }

            if (!empty($dueDate) && strtotime($dueDate) === false) {
                $errors[] = "Due date is invalid.";
            }

            if (empty($errors)) {
                $this->taskModel->create($title, $priority, $status, $dueDate);
                header("Location: index.php");
                exit;
            }
        }

        require "../views/tasks/create.php";
    }
}