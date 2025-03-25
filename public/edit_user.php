<?php
require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/UserController.php';

$controller = new App\Controllers\UserController();
$userId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateUser($userId);
} else {
    $controller->showEditUser($userId);
}