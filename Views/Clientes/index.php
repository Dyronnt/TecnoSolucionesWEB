<?php
require_once __DIR__ . '/../../Config/Config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . APP_URL . '/index.php?controller=auth&action=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/Assets/CSS/bootstrap.min.css">
    <script src="<?= APP_URL ?>/Assets/JS/fontawesome_icons_algoasi.js"></script>
</head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= APP_URL ?>/index.php"><?= APP_NAME ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/index.php?controller=clientes&action=index">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/index.php?controller=proyectos&action=index">Proyectos</a>
                    </li>
                    <?php if ($_SESSION['usuario_rol'] === 'administrador'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/index.php?controller=usuarios&action=index">Usuarios</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i> <?= $_SESSION['usuario_nombre'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small"><i class="fa-solid fa-shield"></i> <?= $_SESSION['usuario_rol'] ?></span></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= APP_URL ?>/index.php?controller=auth&action=logout">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold"><i class="fa-solid fa-users"></i> Clientes</h4>
            <a href="<?= APP_URL ?>/index.php?controller=clientes&action=crear" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Nuevo Cliente
            </a>
        </div>

        <!-- ALERTAS -->
        <?php if (isset($_SESSION['exito'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['exito'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['exito']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- BUSCADOR -->
        <form action="<?= APP_URL ?>/index.php" method="GET" class="mb-4">
            <input type="hidden" name="controller" value="clientes">
            <input type="hidden" name="action" value="buscar">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    name="termino"
                    placeholder="Buscar por nombre, RUC o email..."
                    value="<?= htmlspecialchars($_GET['termino'] ?? '') ?>">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
                <a href="<?= APP_URL ?>/index.php?controller=clientes&action=index" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-xmark"></i> Limpiar
                </a>
            </div>
        </form>

        <!-- TABLA -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>RUC</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Fecha Registro</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clientes)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No se encontraron clientes.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?= $cliente['id'] ?></td>
                                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                                    <td><?= htmlspecialchars($cliente['ruc']) ?></td>
                                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                                    <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                                    <td><?= $cliente['fecha_registro'] ?></td>
                                    <td class="text-center">
                                        <a href="<?= APP_URL ?>/index.php?controller=clientes&action=editar&id=<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                                        <a href="<?= APP_URL ?>/index.php?controller=clientes&action=eliminar&id=<?= $cliente['id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Estás seguro de eliminar este cliente?')"><i class="fa-solid fa-trash"></i> Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="<?= APP_URL ?>/Assets/JS/bootstrap.bundle.min.js"></script>
</body>

</html>