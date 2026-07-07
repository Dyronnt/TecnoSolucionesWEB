<?php

require_once 'Config/Database.php';

class Usuarios
{
    private $conn;
    private $table_name = "USUARIOS";


    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Buscar usuario por email (para el login)
    public function buscarPorEmail($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }



    // READ - Para listar todos los registros o filas
    public function index()
    {
        $query = "SELECT id, nombre, email, rol, fecha_creacion FROM " . $this->table_name . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtener($id)
    {
        $query = "SELECT id, nombre, email, rol, fecha_creacion FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // CREATE - Para insertar nuevo usuario
    public function crear($datos)
    {
        $query = "INSERT INTO " . $this->table_name . " (nombre, email, password, rol)VALUES (:nombre, :email, :password, :rol)";

        $stmt = $this->conn->prepare($query);
        $hash = password_hash($datos['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':nombre',   $datos['nombre']);
        $stmt->bindParam(':email',    $datos['email']);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':rol',      $datos['rol']);
        return $stmt->execute();
    }

    // UPDATE - Para modificar usuario
    public function actualizar($id, $datos)
    {
        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, email = :email, rol = :rol WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id',     $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':email',  $datos['email']);
        $stmt->bindParam(':rol',    $datos['rol']);
        return $stmt->execute();
    }

    // UPDATE - Para cambiar la contraseña
    public function actualizarPassword($id, $nuevaPassword)
    {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $hash = password_hash($nuevaPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(':id',       $id, PDO::PARAM_INT);
        $stmt->bindParam(':password', $hash);
        return $stmt->execute();
    }

    // DELETE - Para eliminar usuarios
    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // SEARCH - Para buscar usuarios
    public function buscar($termino)
    {
        $query = "SELECT id, nombre, email, rol, fecha_creacion FROM " . $this->table_name . "WHERE nombre LIKE :termino OR email LIKE :termino ORDER BY nombre ASC";

        $stmt = $this->conn->prepare($query);
        $termino = "%" . $termino . "%";
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contar()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}
