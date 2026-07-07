<?php

require_once 'Config/Config.php';
require_once 'Models/Usuarios.php';

class AuthController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuarios();
    }

    // Mostrar formulario de login
    public function login()
    {
        // Si ya hay sesión activa, redirigir al dashboard
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php');
            exit;
        }

        require_once 'Views/Auth/login.php';
    }

    // Procesar el formulario de login
    public function autenticar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validar que no estén vacíos
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Por favor complete todos los campos.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Buscar usuario por email
        $usuario = $this->usuarioModel->buscarPorEmail($email);

        // Verificar si existe y si el password es correcto
        if (!$usuario || !password_verify($password, $usuario['password'])) {
            $_SESSION['error'] = "Correo o contraseña incorrectos.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Iniciar sesión
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        $_SESSION['usuario_rol']    = $usuario['rol'];

        header('Location: index.php?controller=dashboard&action=index');
        exit;
    }

    // Cerrar sesión
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
