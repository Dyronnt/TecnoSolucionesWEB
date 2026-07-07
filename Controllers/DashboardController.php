<?php

require_once 'Config/Config.php';
require_once 'Config/Database.php';
require_once 'Models/Clientes.php';
require_once 'Models/Proyectos.php';
require_once 'Models/Usuarios.php';

class DashboardController
{
    private $clienteModel;
    private $proyectoModel;
    private $usuarioModel;

    public function __construct()
    {
        // Proteger acceso sin sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/index.php?controller=auth&action=login');
            exit;
        }

        $this->clienteModel  = new Clientes();
        $this->proyectoModel = new Proyectos();
        $this->usuarioModel  = new Usuarios();
    }

    public function index()
    {
        $totalClientes  = $this->clienteModel->contar();
        $totalProyectos = $this->proyectoModel->contarPorEstado('en curso');
        $totalUsuarios  = $this->usuarioModel->contar();
        $proyectos      = $this->proyectoModel->index();

        require_once 'Views/Dashboard/dashboard.php';
    }
}
