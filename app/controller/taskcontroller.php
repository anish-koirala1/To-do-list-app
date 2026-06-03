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
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $priority = isset($_GET['priority']) ? trim($_GET['priority']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';

        // Validate and sanitize page number
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        // Fetch tasks with error handling
        $tasks = [];
        $error = null;
        try {
            $tasks = $this->taskModel->getAll(
                $search,
                $priority,
                $status,
                $date,
                $limit,
                $offset
            );
        } catch (Exception $e) {
            error_log('Controller error: ' . $e->getMessage());
            $error = "Failed to fetch tasks. Please try again later.";
        }

        // Calculate total pages
        try {
            $total = $this->taskModel->getTotal($search, $priority, $status, $date);
            $totalPages = ceil($total / $limit);
        } catch (Exception $e) {
            $totalPages = 1;
        }

        require "../views/tasks/index.php";
    }

    public function create()
    {
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $priority = isset($_POST['priority']) ? trim($_POST['priority']) : 'Medium';
            $status = isset($_POST['status']) ? trim($_POST['status']) : 'To Do';
            $dueDate = isset($_POST['due_date']) ? trim($_POST['due_date']) : '';

            // Validation
            if (empty($title)) {
                $errors[] = "Title cannot be empty.";
            }

            if (strlen($title) > 255) {
                $errors[] = "Title cannot exceed 255 characters.";
            }

            if (empty($dueDate)) {
                $errors[] = "Due date is required.";
            }

            if (!empty($dueDate) && strtotime($dueDate) === false) {
                $errors[] = "Due date is invalid.";
            }

            // Validate priority
            $validPriorities = ['Low', 'Medium', 'High'];
            if (!in_array($priority, $validPriorities)) {
                $errors[] = "Invalid priority value.";
            }

            // Validate status
            $validStatuses = ['To Do', 'In Progress', 'Done'];
            if (!in_array($status, $validStatuses)) {
                $errors[] = "Invalid status value.";
            }

            // If no errors, try to create
            if (empty($errors)) {
                try {
                    $result = $this->taskModel->create($title, $priority, $status, $dueDate);
                    if ($result) {
                        $success = true;
                        // Redirect after successful creation
                        header("Location: index.php?success=1");
                        exit;
                    } else {
                        $errors[] = "Failed to create task. Please try again.";
                    }
                } catch (Exception $e) {
                    error_log('Controller error during create: ' . $e->getMessage());
                    $errors[] = "An error occurred while creating the task.";
                }
            }
        }

        require "../views/tasks/create.php";
    }
}
