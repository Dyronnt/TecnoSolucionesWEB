<?php

require_once 'Config/Database.php';

class Proyectos
{
    private $conn;
    private $table_name = "PROYECTOS";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // READ - Listar todos con nombre del cliente
    public function index()
    {
        $query = "SELECT p.*, c.nombre AS cliente_nombre 
                  FROM " . $this->table_name . " p
                  INNER JOIN CLIENTES c ON p.cliente_id = c.id
                  ORDER BY p.fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ - Obtener uno por ID
    public function obtener($id)
    {
        $query = "SELECT p.*, c.nombre AS cliente_nombre 
                  FROM " . $this->table_name . " p
                  INNER JOIN CLIENTES c ON p.cliente_id = c.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // CREATE - Insertar nuevo proyecto
    public function crear($datos)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (nombre, descripcion, fecha_inicio, fecha_fin, estado, presupuesto, cliente_id)
                  VALUES (:nombre, :descripcion, :fecha_inicio, :fecha_fin, :estado, :presupuesto, :cliente_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',      $datos['nombre']);
        $stmt->bindParam(':descripcion', $datos['descripcion']);
        $stmt->bindParam(':fecha_inicio',$datos['fecha_inicio']);
        $stmt->bindParam(':fecha_fin',   $datos['fecha_fin']);
        $stmt->bindParam(':estado',      $datos['estado']);
        $stmt->bindParam(':presupuesto', $datos['presupuesto']);
        $stmt->bindParam(':cliente_id',  $datos['cliente_id'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    // UPDATE - Modificar proyecto
    public function actualizar($id, $datos)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, descripcion = :descripcion,
                      fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin,
                      estado = :estado, presupuesto = :presupuesto,
                      cliente_id = :cliente_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id',          $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre',      $datos['nombre']);
        $stmt->bindParam(':descripcion', $datos['descripcion']);
        $stmt->bindParam(':fecha_inicio',$datos['fecha_inicio']);
        $stmt->bindParam(':fecha_fin',   $datos['fecha_fin']);
        $stmt->bindParam(':estado',      $datos['estado']);
        $stmt->bindParam(':presupuesto', $datos['presupuesto']);
        $stmt->bindParam(':cliente_id',  $datos['cliente_id'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    // DELETE - Eliminar proyecto
    public function eliminar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // SEARCH - Buscar proyectos
    public function buscar($termino)
{
    $query = "SELECT p.*, c.nombre AS cliente_nombre
              FROM " . $this->table_name . " p
              INNER JOIN CLIENTES c ON p.cliente_id = c.id
              WHERE p.nombre LIKE :termino1
              OR p.descripcion LIKE :termino2
              OR c.nombre LIKE :termino3
              ORDER BY p.fecha_creacion DESC";

    $stmt = $this->conn->prepare($query);
    $termino = "%" . $termino . "%";
    $stmt->bindParam(':termino1', $termino);
    $stmt->bindParam(':termino2', $termino);
    $stmt->bindParam(':termino3', $termino);
    $stmt->execute();
    return $stmt->fetchAll();
}




    // COUNT - Contar por estado
    public function contarPorEstado($estado)
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                  WHERE estado = :estado";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();
        return $stmt->fetch()['total'];
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