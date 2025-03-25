<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;  // Add this line to import Exception class

class User
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    // Récupérer tous les utilisateurs avec leurs rôles

    public function getAllUsers()
    {
        try {
            $sql = "SELECT users.id, users.username, users.email, users.status, 
                    roles.name AS role
                    FROM users
                    JOIN roles ON users.role_id = roles.id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($users)) {
                return ["message" => "Aucun utilisateur trouvé dans la base de données."];
            }

            return $users;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        }
    }

    public function getUserById($id) {
        try {
            $sql = "SELECT users.*, roles.name AS role 
                    FROM users 
                    JOIN roles ON users.role_id = roles.id 
                    WHERE users.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error getting user: " . $e->getMessage());
        }
    }

    public function updateUser($id, $data) {
        try {
            $sql = "UPDATE users 
                    SET username = :username, 
                        email = :email";
            
            if (!empty($data['password'])) {
                $sql .= ", password = :password";
            }
            
            $sql .= " WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            
            $params = [
                'id' => $id,
                'username' => $data['username'],
                'email' => $data['email']
            ];
            
            if (!empty($data['password'])) {
                $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Error updating user: " . $e->getMessage());
        }
    }

    public function updateUserAdmin($id, $data) {
        try {
            $sql = "UPDATE users 
                    SET username = :username, 
                        email = :email,
                        role_id = :role_id
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'username' => $data['username'],
                'email' => $data['email'],
                'role_id' => $data['role_id']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error updating user: " . $e->getMessage());
        }
    }

    public function getUserSessions($userId) {
        try {
            $sql = "SELECT login_time, logout_time 
                    FROM sessions 
                    WHERE user_id = :user_id 
                    ORDER BY login_time DESC 
                    LIMIT 10";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching user sessions: " . $e->getMessage());
        }
    }

    public function toggleUserStatus($userId) {
        try {
            $sql = "UPDATE users 
                    SET status = CASE 
                        WHEN status = 'active' THEN 'inactive'
                        ELSE 'active'
                    END 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Error toggling user status: " . $e->getMessage());
        }
    }

    public function validateEmail($email) {
        try {
            $sql = "SELECT id FROM users WHERE email = :email AND id != :current_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'email' => $email,
                'current_id' => $_SESSION['user_id'] ?? 0
            ]);
            return $stmt->fetch() === false;
        } catch (PDOException $e) {
            throw new Exception("Error validating email: " . $e->getMessage());
        }
    }

    public function validateUsername($username) {
        try {
            $sql = "SELECT id FROM users WHERE username = :username AND id != :current_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'current_id' => $_SESSION['user_id'] ?? 0
            ]);
            return $stmt->fetch() === false;
        } catch (PDOException $e) {
            throw new Exception("Error validating username: " . $e->getMessage());
        }
    }

    public function deleteUser($userId) {
        try {
            // First delete related sessions
            $sql = "DELETE FROM sessions WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);

            // Then delete the user
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
        }
    }

    public function createDefaultAdmin() {
        try {
            // Check if admin exists
            $sql = "SELECT id FROM users WHERE email = 'admin@admin.com'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            if ($stmt->fetch() === false) {
                // Create admin user
                $sql = "INSERT INTO users (username, email, password, role_id, status) 
                        VALUES (:username, :email, :password, :role_id, 'active')";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'username' => 'admin',
                    'email' => 'admin@admin.com',
                    'password' => password_hash('Admin123!', PASSWORD_BCRYPT),
                    'role_id' => 1 // Assuming 1 is the admin role_id
                ]);
            }
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error creating default admin: " . $e->getMessage());
        }
    }

    public function createUser($data) {
        try {
            // Validate username and email
            if (!$this->validateUsername($data['username'])) {
                throw new \Exception("Username already exists");
            }
            if (!$this->validateEmail($data['email'])) {
                throw new \Exception("Email already exists");
            }

            $sql = "INSERT INTO users (username, email, password, role_id, status) 
                    VALUES (:username, :email, :password, :role_id, 'active')";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'role_id' => $data['role_id']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error creating user: " . $e->getMessage());
        }
    }
}
