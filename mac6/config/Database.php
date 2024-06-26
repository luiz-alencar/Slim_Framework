<?php
class Database {
    private $host = "127.0.0.1";
    private $port = "3308"; // Porta padrão do MySQL/MariaDB
    private $user = "root";
    private $db = "tarefa";
    private $pwd = "123";
    private $conn = NULL;

    public function connect() {

        try{
            $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->db", $this->user, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
