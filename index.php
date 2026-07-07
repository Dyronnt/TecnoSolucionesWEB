<?php
require_once 'Config/Config.php';
require_once 'Config/Database.php';
session_name(SESSION_NAME);
session_start();

$controller = $_GET['controller'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

switch ($controller) {
    case 'dashboard':
        require_once 'Controllers/DashboardController.php';
        $ctrl = new DashboardController();
        break;
    case 'clientes':
        require_once 'Controllers/ClientesController.php';
        $ctrl = new ClientesController();
        break;
    case 'proyectos':
        require_once 'Controllers/ProyectosController.php';
        $ctrl = new ProyectosController();
        break;
    case 'usuarios':
        require_once 'Controllers/UsuariosController.php';
        $ctrl = new UsuariosController();
        break;
    case 'auth':
        require_once 'Controllers/AuthController.php';
        $ctrl = new AuthController();
        break;

    case 'reportes':
        require_once 'Controllers/ReportesController.php';
        $ctrl = new ReportesController();
        break;

    default:
        require_once 'Controllers/DashboardController.php';
        $ctrl = new DashboardController();
        break;
}

$ctrl->{$action}();
