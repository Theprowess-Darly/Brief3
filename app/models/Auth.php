<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class Auth {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function login($email, $password) {
        try {
            // Debug query to check user and role data
            $sql = "SELECT u.*, r.name as role, r.id as role_id
                    FROM users u 
                    LEFT JOIN roles r ON u.role_id = r.id 
                    WHERE u.email = :email AND u.status = 'active'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug logs
            error_log("Login attempt for email: " . $email);
            if ($user) {
                error_log("User found: " . print_r($user, true));
                error_log("Password verification result: " . (password_verify($password, $user['password']) ? 'true' : 'false'));
            } else {
                error_log("No user found with email: " . $email);
            }

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Log successful login
                    error_log("Successful login for user ID: " . $user['id'] . " with role: " . $user['role']);
                    
                    // Log the session
                    $sql = "INSERT INTO sessions (user_id, login_time) VALUES (:user_id, NOW())";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(['user_id' => $user['id']]);
                    
                    return $user;
                }
                error_log("Password verification failed for user: " . $user['email']);
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            throw new Exception("Login error: " . $e->getMessage());
        }
    }

    public function createDefaultAdmin() {
        try {
            // First, ensure the roles table has the admin role
            $sql = "INSERT IGNORE INTO roles (id, name) VALUES (1, 'administrateur')";
            $this->db->exec($sql);

            // Check if admin exists
            $sql = "SELECT id FROM users WHERE role_id = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $existingAdmin = $stmt->fetch();

            if ($existingAdmin) {
                // Update existing admin instead of deleting
                $sql = "UPDATE users 
                        SET username = :username,
                            email = :email,
                            status = 'active'
                        WHERE role_id = 1";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'username' => 'admin',
                    'email' => 'admin@example.com'
                ]);
            } else {
                // Create new admin user
                $sql = "INSERT INTO users (username, email, password, role_id, status) 
                        VALUES (:username, :email, :password, :role_id, 'active')";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'username' => 'admin',
                    'email' => 'admin@example.com',
                    'password' => password_hash('Admin123!', PASSWORD_BCRYPT),
                    'role_id' => 1
                ]);
            }
        } catch (PDOException $e) {
            throw new Exception("Error managing default admin: " . $e->getMessage());
        }
    }

    public function register($username, $email, $password) {
        try {
            // Check if email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                throw new Exception("Email already exists");
            }

            // Get client role_id
            $stmt = $this->db->prepare("SELECT id FROM roles WHERE name = 'client'");
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);

            // Insert new user
            $sql = "INSERT INTO users (username, email, password, role_id) 
                    VALUES (:username, :email, :password, :role_id)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role_id' => $role['id']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Registration error: " . $e->getMessage());
        }
    }

    private function logSession($userId) {
        try {
            $sql = "INSERT INTO sessions (user_id) VALUES (:user_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['user_id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Session logging error: " . $e->getMessage());
        }
    }

    public function logout($userId) {
        try {
            $sql = "UPDATE sessions 
                    SET logout_time = CURRENT_TIMESTAMP 
                    WHERE user_id = :user_id 
                    AND logout_time IS NULL";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['user_id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Logout error: " . $e->getMessage());
        }
    }
}