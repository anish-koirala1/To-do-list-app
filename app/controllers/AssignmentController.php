<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Assignment.php";

class AssignmentController
{
    private $assignment;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $database = new Database();

        $db = $database->connect();

        $this->assignment = new Assignment($db);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK LOGIN SESSION
    |--------------------------------------------------------------------------
    */
    private function checkLogin()
    {
        if (!isset($_SESSION['user'])) {

            header("Location: index.php?action=login");

            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Show Assignment List
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->checkLogin();

        $assignments = $this->assignment->getAll();

        require __DIR__ . "/../views/assignments/index.php";
    }

    /*
    |--------------------------------------------------------------------------
    | Create Assignment
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->checkLogin();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $this->assignment->create(
                $_POST["task_name"],
                $_POST["assigned_to"],
                $_POST["due_date"],
                $_POST["status"]
            );

            header("Location: index.php?action=index");

            exit;
        }

        require __DIR__ . "/../views/assignments/create.php";
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Assignment
    |--------------------------------------------------------------------------
    */
    public function edit()
    {
        $this->checkLogin();

        $id = $_GET["id"] ?? null;

        if (!$id) {

            header("Location: index.php?action=index");

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Assignment
        |--------------------------------------------------------------------------
        */
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $this->assignment->update(
                $id,
                $_POST["task_name"],
                $_POST["assigned_to"],
                $_POST["due_date"],
                $_POST["status"]
            );

            header("Location: index.php?action=index");

            exit;
        }

        $assignment = $this->assignment->getById($id);

        require __DIR__ . "/../views/assignments/edit.php";
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Assignment
    |--------------------------------------------------------------------------
    */
    public function delete()
    {
        $this->checkLogin();

        $id = $_GET["id"] ?? null;

        if (!$id) {

            header("Location: index.php?action=index");

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | WHEN DELETE CONFIRM BUTTON CLICKED
        |--------------------------------------------------------------------------
        */
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $this->assignment->delete($id);

            header("Location: index.php?action=index");

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | SHOW DELETE CONFIRMATION PAGE
        |--------------------------------------------------------------------------
        */
        $assignment = $this->assignment->getById($id);

        require __DIR__ . "/../views/assignments/delete.php";
    }

    /*
    |--------------------------------------------------------------------------
    | Search Assignment
    |--------------------------------------------------------------------------
    */
    public function search()
    {
        $this->checkLogin();

        $assignments = [];

        if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {

            $id = $_GET['search_id'];

            $assignments = $this->assignment->searchById($id);
        }

        require __DIR__ . "/../views/assignments/search.php";
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Assignment By Status
    |--------------------------------------------------------------------------
    */
    public function filter()
    {
        $this->checkLogin();

        $status = $_GET['status'] ?? '';

        $from_date = $_GET['from_date'] ?? '';

        $to_date = $_GET['to_date'] ?? '';

        $assignments = $this->assignment
            ->filterAssignments($status, $from_date, $to_date);

        require __DIR__ . "/../views/assignments/filter.php";
    }
}
?>
