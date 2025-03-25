<?php
require_once '../app/config/Database.php';
require_once '../app/models/Auth.php';
require_once '../app/controllers/AuthController.php';

$controller = new App\Controllers\AuthController();
$controller->logout();