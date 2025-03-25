<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Config\Database;

class AuthController {
    private $authModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->authModel = new Auth($db);
        
        // Create default admin account if it doesn't exist
        $this->authModel->createDefaultAdmin();
    }

    public function showLogin() {
        require_once '../app/views/auth/login.php';
    }

    public function showRegister() {
        require_once '../app/views/auth/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                $user = $this->authModel->login($email, $password);
                if ($user) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    header('Location: /PHP/Brief3/public/dashboard.php');
                    exit;
                } else {
                    $error = "Invalid credentials";
                    require_once '../app/views/auth/login.php';
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                require_once '../app/views/auth/login.php';
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                if ($this->authModel->register($username, $email, $password)) {
                    header('Location: /PHP/Brief3/public/login.php');
                    exit;
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                require_once '../app/views/auth/register.php';
            }
        }
    }

    public function logout() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            $this->authModel->logout($_SESSION['user_id']);
            session_destroy();
        }
        header('Location: /PHP/Brief3/public/login.php');
        exit;
    }
}