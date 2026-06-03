<?php

require_once "../config/database.php";
require_once "../controller/taskcontroller.php";

$controller = new TaskController($pdo);

$action = $_GET['action'] ?? 'index';

if ($action === 'create') {
    $controller->create();
} else {
    $controller->index();
}