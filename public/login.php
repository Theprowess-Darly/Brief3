<?php
require_once '../app/config/Database.php';
require_once '../app/models/Auth.php';
require_once '../app/controllers/AuthController.php';

$controller = new App\Controllers\AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->showLogin();
}