<?php

class Database
{
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "tecnosolucionesweb";
    private $conn;
    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            

            // echo "Conectado correctamente a la base de datos."; // Está comentado para que no se muestre a cada rato.
        } catch (PDOException $ex) {
            error_log($ex->getMessage()); 
            die("Error de conexión. Contacte al administrador.");
        }
        return $this->conn;
    }
}
