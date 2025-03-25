<?php

namespace App\Controllers;

use App\Models\User;
use App\Config\Database;

class DashboardController {
    private $userModel;

    public function __construct() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /PHP/Brief3/public/login.php');
            exit;
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new User($db);
    }

    public function index() {
        if ($_SESSION['role'] === 'administrateur') {
            $users = $this->userModel->getAllUsers();
            require_once '../app/views/dashboard/admin.php';
        } else {
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            $sessions = $this->userModel->getUserSessions($_SESSION['user_id']);
            require_once '../app/views/dashboard/client.php';
        }
    }

    public function getUserSessions($userId) {
        if ($_SESSION['user_id'] !== $userId && $_SESSION['role'] !== 'administrateur') {
            header('Location: /PHP/Brief3/public/dashboard.php');
            exit;
        }
        return $this->userModel->getUserSessions($userId);
    }
}