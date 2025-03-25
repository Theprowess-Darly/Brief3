<?php

namespace App\Controllers;

use App\Models\User;
use App\Config\Database;

class UserController {
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

    public function showEditProfile() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        require_once '../app/views/profile/edit.php';
    }

    public function updateProfile() {
        try {
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];

            if ($this->userModel->updateUser($_SESSION['user_id'], $data)) {
                $_SESSION['username'] = $data['username'];
                $success = "Profile updated successfully";
            }

            $user = $this->userModel->getUserById($_SESSION['user_id']);
            require_once '../app/views/profile/edit.php';
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            require_once '../app/views/profile/edit.php';
        }
    }

    public function showEditUser($userId) {
        if ($_SESSION['role'] !== 'administrateur') {
            header('Location: /PHP/Brief3/public/dashboard.php');
            exit;
        }
        
        try {
            $user = $this->userModel->getUserById($userId);
            if (!$user) {
                header('Location: /PHP/Brief3/public/index.php');
                exit;
            }
            require_once '../app/views/users/edit.php';
        } catch (\Exception $e) {
            $error = $e->getMessage();
            require_once '../app/views/users/edit.php';
        }
    }

    public function updateUser($userId) {
        if ($_SESSION['role'] !== 'administrateur') {
            header('Location: /PHP/Brief3/public/dashboard.php');
            exit;
        }

        try {
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role_id' => $_POST['role_id'] ?? ''
            ];

            // Validate username and email
            if (!$this->userModel->validateUsername($data['username'])) {
                throw new \Exception("Username already exists");
            }
            if (!$this->userModel->validateEmail($data['email'])) {
                throw new \Exception("Email already exists");
            }

            if ($this->userModel->updateUserAdmin($userId, $data)) {
                header('Location: /PHP/Brief3/public/index.php');
                exit;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $user = $this->userModel->getUserById($userId);
            require_once '../app/views/users/edit.php';
        }
    }

    public function deleteUser($userId) {
        if ($_SESSION['role'] !== 'administrateur') {
            header('Location: /PHP/Brief3/public/dashboard.php');
            exit;
        }

        try {
            if ($userId == $_SESSION['user_id']) {
                throw new \Exception("Cannot delete your own account");
            }

            if ($this->userModel->deleteUser($userId)) {
                header('Location: /PHP/Brief3/public/index.php');
                exit;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            header('Location: /PHP/Brief3/public/index.php?error=' . urlencode($error));
        }
    }

    public function toggleStatus($userId) {
        if ($_SESSION['role'] !== 'administrateur') {
            header('Location: /PHP/Brief3/public/dashboard.php');
            exit;
        }

        try {
            if ($this->userModel->toggleUserStatus($userId)) {
                header('Location: /PHP/Brief3/public/dashboard.php');
                exit;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            header('Location: /PHP/Brief3/public/dashboard.php?error=' . urlencode($error));
            exit;
        }
    }

    public function showAddUser() {
        if ($_SESSION['role'] !== 'administrateur') {
            header('Location: /PHP/Brief3/public/dashboard.php');
            exit;
        }
        require_once '../app/views/users/add.php';
    }

    public function addUser() {
        if ($_SESSION['role'] !== 'administrateur') {
            header('Location: /PHP/Brief3/public/dashboard.php');
            exit;
        }

        try {
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role_id' => $_POST['role_id'] ?? 2
            ];

            if ($this->userModel->createUser($data)) {
                header('Location: /PHP/Brief3/public/dashboard.php');
                exit;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            require_once '../app/views/users/add.php';
        }
    }
}