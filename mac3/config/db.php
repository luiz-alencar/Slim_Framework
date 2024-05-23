<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $db = "mysql";
    private $pwd = "123";
    private $conn = NULL;

    public function conectar() {

        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}