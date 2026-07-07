<?php

require_once 'Config/Database.php';

class Clientes
{
    private $conn;
    private $table_name = "CLIENTES";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // READ - Listar todos
    public function index()
    {
        $query = "SELECT * FROM {$this->table_name} ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ - Obtener uno por ID
    public function obtener($id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // CREATE - Insertar nuevo cliente
    public function crear($datos)
    {
        $query = "INSERT INTO {$this->table_name}(nombre, ruc, telefono, email, direccion)
                    VALUES(:nombre, :ruc, :telefono, :email, :direccion)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',   $datos['nombre']);
        $stmt->bindParam(':ruc',      $datos['ruc']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':email',    $datos['email']);
        $stmt->bindParam(':direccion', $datos['direccion']);
        return $stmt->execute();
    }

    // UPDATE - Modificar cliente
    public function actualizar($id, $datos)
    {
        $query = "UPDATE {$this->table_name} SET 
                    nombre = :nombre,
                    ruc = :ruc,
                    telefono = :telefono,
                    email = :email,
                    direccion = :direccion
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id',       $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre',   $datos['nombre']);
        $stmt->bindParam(':ruc',      $datos['ruc']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':email',    $datos['email']);
        $stmt->bindParam(':direccion', $datos['direccion']);
        return $stmt->execute();
    }

    // DELETE - Eliminar cliente
    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // SEARCH - Buscar clientes
    public function buscar($termino)
    {
        $query = "SELECT * FROM {$this->table_name} 
        WHERE 
        nombre LIKE :termino1 OR 
        ruc LIKE :termino2 OR 
        email LIKE :termino3 
        ORDER BY nombre ASC";

        $stmt = $this->conn->prepare($query);
        $termino = "%" . $termino . "%";
        $stmt->bindParam(':termino1', $termino);
        $stmt->bindParam(':termino2', $termino);
        $stmt->bindParam(':termino3', $termino);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // COUNT - Contar todos
    public function contar()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}
