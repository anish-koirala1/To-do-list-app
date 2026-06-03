<?php

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/controller/TaskController.php';

$controller = new TaskController(getDB());
$action = $_GET['action'] ?? 'index';

if ($action === 'create') {
    $controller->create();
} else {
    $controller->index();
}
