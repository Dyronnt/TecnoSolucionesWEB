<?php

require_once 'Config/Config.php';
require_once 'Models/Clientes.php';

class ClientesController
{
    private $clienteModel;

    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/index.php?controller=auth&action=login');
            exit;
        }

        $this->clienteModel = new Clientes();
    }

    // READ - Listar todos
    public function index()
    {
        $clientes = $this->clienteModel->index();
        require_once 'Views/Clientes/index.php';
    }

    // SEARCH - Buscar clientes
    public function buscar()
    {
        $termino  = trim($_GET['termino'] ?? '');
        $clientes = $this->clienteModel->buscar($termino);
        require_once 'Views/Clientes/index.php';
    }

    // CREATE - Mostrar formulario
    public function crear()
    {
        require_once 'Views/Clientes/crear.php';
    }

    // CREATE - Procesar formulario
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
            exit;
        }

        $datos = [
            'nombre'    => trim($_POST['nombre']    ?? ''),
            'ruc'       => trim($_POST['ruc']       ?? ''),
            'telefono'  => trim($_POST['telefono']  ?? ''),
            'email'     => trim($_POST['email']     ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
        ];

        if (empty($datos['nombre']) || empty($datos['ruc'])) {
            $_SESSION['error'] = "Nombre y RUC son obligatorios.";
            header('Location: ' . APP_URL . '/index.php?controller=clientes&action=crear');
            exit;
        }

        if ($this->clienteModel->crear($datos)) {
            $_SESSION['exito'] = "Cliente registrado correctamente.";
        } else {
            $_SESSION['error'] = "Error al registrar el cliente.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
        exit;
    }

    // UPDATE - Mostrar formulario
    public function editar()
    {
        $id      = $_GET['id'] ?? null;
        $cliente = $this->clienteModel->obtener($id);

        if (!$cliente) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
            exit;
        }

        require_once 'Views/Clientes/editar.php';
    }

    // UPDATE - Procesar formulario
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
            exit;
        }

        $id    = $_POST['id'] ?? null;
        $datos = [
            'nombre'    => trim($_POST['nombre']    ?? ''),
            'ruc'       => trim($_POST['ruc']       ?? ''),
            'telefono'  => trim($_POST['telefono']  ?? ''),
            'email'     => trim($_POST['email']     ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
        ];

        if (empty($datos['nombre']) || empty($datos['ruc'])) {
            $_SESSION['error'] = "Nombre y RUC son obligatorios.";
            header('Location: ' . APP_URL . '/index.php?controller=clientes&action=editar&id=' . $id);
            exit;
        }

        if ($this->clienteModel->actualizar($id, $datos)) {
            $_SESSION['exito'] = "Cliente actualizado correctamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el cliente.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
        exit;
    }

    // DELETE - Eliminar cliente
    public function eliminar()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Cliente no válido.";
            header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
            exit;
        }

        if ($this->clienteModel->eliminar($id)) {
            $_SESSION['exito'] = "Cliente eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el cliente. Puede tener proyectos asociados.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
        exit;
    }
}
