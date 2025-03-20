<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'brief_3';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname}",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception("Erreur de connexion : " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
?>
