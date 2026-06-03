<?php

require_once __DIR__ . '/../model/Assignment.php';

class AssignmentController
{
    private $assignment;

    public function __construct($db)
    {
        $this->assignment = new Assignment($db);
    }

    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../../auth/login.php');
            exit;
        }
    }

    public function index()
    {
        $this->checkLogin();
        $assignments = $this->assignment->getAll();
        require __DIR__ . '/../views/index.php';
    }

    public function create()
    {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignment->create(
                trim($_POST['task_name'] ?? ''),
                trim($_POST['assigned_to'] ?? ''),
                $_POST['due_date'] ?? '',
                $_POST['status'] ?? 'Pending'
            );
            header('Location: index.php?action=index');
            exit;
        }

        require __DIR__ . '/../views/create.php';
    }

    public function edit()
    {
        $this->checkLogin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignment->update(
                $id,
                trim($_POST['task_name'] ?? ''),
                trim($_POST['assigned_to'] ?? ''),
                $_POST['due_date'] ?? '',
                $_POST['status'] ?? 'Pending'
            );
            header('Location: index.php?action=index');
            exit;
        }

        $assignment = $this->assignment->getById($id);
        if (!$assignment) {
            header('Location: index.php?action=index');
            exit;
        }

        require __DIR__ . '/../views/edit.php';
    }

    public function delete()
    {
        $this->checkLogin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignment->delete($id);
            header('Location: index.php?action=index');
            exit;
        }

        $assignment = $this->assignment->getById($id);
        if (!$assignment) {
            header('Location: index.php?action=index');
            exit;
        }

        require __DIR__ . '/../views/delete.php';
    }

    public function search()
    {
        $this->checkLogin();

        $assignments = [];
        if (isset($_GET['search_id']) && $_GET['search_id'] !== '') {
            $assignments = $this->assignment->searchById((int)$_GET['search_id']);
        }

        require __DIR__ . '/../views/search.php';
    }

    public function filter()
    {
        $this->checkLogin();

        $status = $_GET['status'] ?? '';
        $from_date = $_GET['from_date'] ?? '';
        $to_date = $_GET['to_date'] ?? '';

        $assignments = $this->assignment->filterAssignments($status, $from_date, $to_date);
        require __DIR__ . '/../views/filter.php';
    }
}
