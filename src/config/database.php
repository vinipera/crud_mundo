<?php
class Database {
    private static $instance = null;
    private $pdo;
    private $mysqli;

    private function __construct() {
        try {
            // Conexão PDO
            $this->pdo = new PDO("mysql:host=localhost;dbname=crud_mundo;charset=utf8", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Conexão MySQLi (para compatibilidade)
            $this->mysqli = new mysqli("localhost", "root", "", "crud_mundo");
            $this->mysqli->set_charset("utf8");
            
            if ($this->mysqli->connect_error) {
                throw new Exception("MySQLi connection failed: " . $this->mysqli->connect_error);
            }
            
        } catch (PDOException $e) {
            die("Falha na conexão PDO: " . $e->getMessage());
        } catch (Exception $e) {
            die("Falha na conexão MySQLi: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function getMysqli() {
        return $this->mysqli;
    }
}
?>
