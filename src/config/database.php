<?php
class database {
    private static $instance = null;
    private $pdo;
    private $mysqli;

    private function __construct() {
        try {
            // cria conexão com banco de dados
            $this->pdo = new pdo("mysql:host=localhost;dbname=crud_mundo;charset=utf8", "root", "");
            $this->pdo->setattribute(pdo::ATTR_ERRMODE, pdo::ERRMODE_EXCEPTION);
            
            // cria conexão mysqli para compatibilidade
            $this->mysqli = new mysqli("localhost", "root", "", "crud_mundo");
            $this->mysqli->set_charset("utf8");
            
            // verifica se conexão mysqli funcionou
            if ($this->mysqli->connect_error) {
                throw new exception("mysqli connection failed: " . $this->mysqli->connect_error);
            }
            
        } catch (pdoexception $e) {
            // trata erro na conexão pdo
            die("falha na conexão pdo: " . $e->getmessage());
        } catch (exception $e) {
            // trata erro na conexão mysqli
            die("falha na conexão mysqli: " . $e->getmessage());
        }
    }

    public static function getinstance() {
        // retorna instância única da classe (singleton)
        if (self::$instance === null) {
            self::$instance = new database();
        }
        return self::$instance;
    }

    public function getpdo() {
        // retorna objeto pdo para operações no banco
        return $this->pdo;
    }

    public function getmysqli() {
        // retorna objeto mysqli para operações no banco
        return $this->mysqli;
    }
}
?>