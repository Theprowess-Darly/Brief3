<?php
require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/UserController.php';

$controller = new App\Controllers\UserController();
$userId = $_GET['id'] ?? null;

if ($userId) {
    $controller->toggleStatus($userId);
} else {
    header('Location: /PHP/Brief3/public/index.php');
}