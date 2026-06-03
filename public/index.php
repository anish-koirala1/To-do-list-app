<?php

session_start();

require_once "../config/database.php";

require_once "../app/controllers/AssignmentController.php";
require_once "../app/controllers/AuthController.php";

$db = (new Database())->connect();

$assignmentController = new AssignmentController($db);
$authController = new AuthController($db);

$action = $_GET['action'] ?? 'login';

switch ($action) {

    case 'login':
        $authController->login();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'index':
        $assignmentController->index();
        break;

    case 'create':
        $assignmentController->create();
        break;

    case 'store':
        $assignmentController->store();
        break;

    case 'edit':
        $assignmentController->edit();
        break;

    case 'update':
        $assignmentController->update();
        break;

    case 'delete':
        $assignmentController->delete();
        break;

    case 'search':
        $assignmentController->search();
        break;

    case 'filter':
        $assignmentController->filter();
        break;

    default:
        echo "404 Page Not Found";
        break;
}