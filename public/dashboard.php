<?php
require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/DashboardController.php';

$controller = new App\Controllers\DashboardController();
$controller->index();