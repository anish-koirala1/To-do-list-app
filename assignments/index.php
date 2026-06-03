<?php

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/controller/AssignmentController.php';

$controller = new AssignmentController(getDB());
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'search':
        $controller->search();
        break;
    case 'filter':
        $controller->filter();
        break;
    default:
        $controller->index();
        break;
}
