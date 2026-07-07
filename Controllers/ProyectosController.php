<?php

require_once 'Config/Config.php';
require_once 'Models/Proyectos.php';
require_once 'Models/Clientes.php';

class ProyectosController
{
    private $proyectoModel;
    private $clienteModel;

    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/index.php?controller=auth&action=login');
            exit;
        }

        $this->proyectoModel = new Proyectos();
        $this->clienteModel  = new Clientes();
    }

    // READ - Listar todos
    public function index()
    {
        $proyectos = $this->proyectoModel->index();
        require_once 'Views/Proyectos/index.php';
    }

    // SEARCH - Buscar proyectos
    public function buscar()
    {
        $termino   = trim($_GET['termino'] ?? '');
        $proyectos = $this->proyectoModel->buscar($termino);
        require_once 'Views/Proyectos/index.php';
    }

    // CREATE - Mostrar formulario
    public function crear()
    {
        $clientes = $this->clienteModel->index();
        require_once 'Views/Proyectos/crear.php';
    }

    // CREATE - Procesar formulario
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=index');
            exit;
        }

        $datos = [
            'nombre'      => trim($_POST['nombre']      ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'fecha_inicio' => trim($_POST['fecha_inicio'] ?? ''),
            'fecha_fin'   => !empty($_POST['fecha_fin']) ? trim($_POST['fecha_fin']) : null,
            'estado'      => trim($_POST['estado']      ?? 'pendiente'),
            'presupuesto' => !empty($_POST['presupuesto']) ? trim($_POST['presupuesto']) : null,
            'cliente_id'  => trim($_POST['cliente_id']  ?? ''),
        ];

        if (empty($datos['nombre']) || empty($datos['fecha_inicio']) || empty($datos['cliente_id'])) {
            $_SESSION['error'] = "Nombre, fecha de inicio y cliente son obligatorios.";
            header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=crear');
            exit;
        }

        if ($this->proyectoModel->crear($datos)) {
            $_SESSION['exito'] = "Proyecto registrado correctamente.";
        } else {
            $_SESSION['error'] = "Error al registrar el proyecto.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=index');
        exit;
    }

    // UPDATE - Mostrar formulario
    public function editar()
    {
        $id       = $_GET['id'] ?? null;
        $proyecto = $this->proyectoModel->obtener($id);

        if (!$proyecto) {
            $_SESSION['error'] = "Proyecto no encontrado.";
            header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=index');
            exit;
        }

        $clientes = $this->clienteModel->index();
        require_once 'Views/Proyectos/editar.php';
    }

    // UPDATE - Procesar formulario
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=index');
            exit;
        }

        $id    = $_POST['id'] ?? null;
        $datos = [
            'nombre'      => trim($_POST['nombre']      ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'fecha_inicio' => trim($_POST['fecha_inicio'] ?? ''),
            'fecha_fin'   => !empty($_POST['fecha_fin']) ? trim($_POST['fecha_fin']) : null,
            'estado'      => trim($_POST['estado']      ?? 'pendiente'),
            'presupuesto' => !empty($_POST['presupuesto']) ? trim($_POST['presupuesto']) : null,
            'cliente_id'  => trim($_POST['cliente_id']  ?? ''),
        ];

        if (empty($datos['nombre']) || empty($datos['fecha_inicio']) || empty($datos['cliente_id'])) {
            $_SESSION['error'] = "Nombre, fecha de inicio y cliente son obligatorios.";
            header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=editar&id=' . $id);
            exit;
        }

        if ($this->proyectoModel->actualizar($id, $datos)) {
            $_SESSION['exito'] = "Proyecto actualizado correctamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el proyecto.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=index');
        exit;
    }

    // DELETE - Eliminar proyecto
    public function eliminar()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Proyecto no válido.";
            header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=index');
            exit;
        }

        if ($this->proyectoModel->eliminar($id)) {
            $_SESSION['exito'] = "Proyecto eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el proyecto.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=proyectos&action=index');
        exit;
    }
}
