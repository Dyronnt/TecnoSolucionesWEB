<?php

require_once 'Config/Config.php';
require_once 'Models/Usuarios.php';

class UsuariosController
{
    private $usuarioModel;

    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/index.php?controller=auth&action=login');
            exit;
        }

        $action = $_GET['action'] ?? 'index';

        // Solo administradores pueden gestionar usuarios, excepto el mensaje especial
        if ($action !== 'mensaje' && $_SESSION['usuario_rol'] !== 'administrador') {
            header('Location: ' . APP_URL . '/index.php?controller=dashboard&action=index');
            exit;
        }

        $this->usuarioModel = new Usuarios();
    }

    // READ - Listar todos
    public function index()
    {
        $usuarios = $this->usuarioModel->index();
        require_once 'Views/Usuarios/index.php';
    }

    // SEARCH - Buscar usuarios
    public function buscar()
    {
        $termino  = trim($_GET['termino'] ?? '');
        $usuarios = $this->usuarioModel->buscar($termino);
        require_once 'Views/Usuarios/index.php';
    }

    // CREATE - Mostrar formulario
    public function crear()
    {
        require_once 'Views/Usuarios/crear.php';
    }

    // CREATE - Procesar formulario
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
            exit;
        }

        $datos = [
            'nombre'   => trim($_POST['nombre']   ?? ''),
            'email'    => trim($_POST['email']    ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'rol'      => trim($_POST['rol']      ?? 'empleado'),
        ];

        if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['password'])) {
            $_SESSION['error'] = "Nombre, email y contraseña son obligatorios.";
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=crear');
            exit;
        }

        // Verificar si el email ya existe
        if ($this->usuarioModel->buscarPorEmail($datos['email'])) {
            $_SESSION['error'] = "Ya existe un usuario con ese correo electrónico.";
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=crear');
            exit;
        }

        if ($this->usuarioModel->crear($datos)) {
            $_SESSION['exito'] = "Usuario registrado correctamente.";
        } else {
            $_SESSION['error'] = "Error al registrar el usuario.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
        exit;
    }

    // UPDATE - Mostrar formulario
    public function editar()
    {
        $id      = $_GET['id'] ?? null;
        $usuario = $this->usuarioModel->obtener($id);

        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado.";
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
            exit;
        }

        require_once 'Views/Usuarios/editar.php';
    }

    // UPDATE - Procesar formulario
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
            exit;
        }

        $id    = $_POST['id'] ?? null;
        $datos = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email'  => trim($_POST['email']  ?? ''),
            'rol'    => trim($_POST['rol']    ?? 'empleado'),
        ];

        if (empty($datos['nombre']) || empty($datos['email'])) {
            $_SESSION['error'] = "Nombre y email son obligatorios.";
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=editar&id=' . $id);
            exit;
        }

        if ($this->usuarioModel->actualizar($id, $datos)) {
            $_SESSION['exito'] = "Usuario actualizado correctamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el usuario.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
        exit;
    }

    // UPDATE - Cambiar contraseña
    public function cambiarPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
            exit;
        }

        $id              = $_POST['id']               ?? null;
        $nuevaPassword   = trim($_POST['password']    ?? '');
        $confirmar       = trim($_POST['confirmar']   ?? '');

        if (empty($nuevaPassword) || empty($confirmar)) {
            $_SESSION['error'] = "Ambos campos de contraseña son obligatorios.";
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=editar&id=' . $id);
            exit;
        }

        if ($nuevaPassword !== $confirmar) {
            $_SESSION['error'] = "Las contraseñas no coinciden.";
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=editar&id=' . $id);
            exit;
        }

        if ($this->usuarioModel->actualizarPassword($id, $nuevaPassword)) {
            $_SESSION['exito'] = "Contraseña actualizada correctamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar la contraseña.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
        exit;
    }

    // DELETE - Eliminar usuario
    public function eliminar()
    {
        $id = $_GET['id'] ?? null;

        // Evitar que el admin se elimine a sí mismo
        if ($id == $_SESSION['usuario_id']) {
            $_SESSION['error'] = "No puedes eliminar tu propio usuario.";
            header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
            exit;
        }

        if ($this->usuarioModel->eliminar($id)) {
            $_SESSION['exito'] = "Usuario eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario.";
        }

        header('Location: ' . APP_URL . '/index.php?controller=usuarios&action=index');
        exit;
    }

    // Mensaje - Para Julio Flores !!!

    public function mensaje()
    {
        require_once 'Views/Usuarios/mensaje.php';
    }
}
