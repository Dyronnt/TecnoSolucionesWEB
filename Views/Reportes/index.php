<?php
require_once __DIR__ . '/../../Config/Config.php';

/** @var array $proyectos */



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
    <title>Reportes - <?= APP_NAME ?></title>
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
                            <i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
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

        <div class="mb-4">
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf"></i> Reportes</h4>
            <p class="text-muted mb-0"><i class="fa-solid fa-download"></i> Descarga reportes en PDF para la gerencia.</p>
        </div>

        <!-- ALERTAS -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="row g-4">

            <!-- Reporte 1: Todos los proyectos -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="fs-1">📋</span>
                        </div>
                        <h5 class="card-title fw-bold">Todos los proyectos</h5>
                        <p class="card-text text-muted flex-grow-1">
                            Listado completo de proyectos con estado, fechas y cliente asignado.
                        </p>
                        <a href="<?= APP_URL ?>/index.php?controller=reportes&action=proyectos"
                            class="btn btn-primary mt-2" target="_blank">
                            <i class="fa-solid fa-download"></i> Descargar PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reporte 2: Detalle de proyecto -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="fs-1">🔍</span>
                        </div>
                        <h5 class="card-title fw-bold">Detalle de proyecto</h5>
                        <p class="card-text text-muted">
                            Informe detallado de un proyecto específico.
                        </p>
                        <select class="form-select mb-2" id="selectProyecto">
                            <option value="">— Selecciona un proyecto —</option>
                            <?php foreach ($proyectos as $p): ?>
                                <option value="<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary mt-auto" onclick="descargarDetalle()">
                            <i class="fa-solid fa-download"></i> Descargar PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reporte 3: Clientes con proyectos -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="fs-1">🏢</span>
                        </div>
                        <h5 class="card-title fw-bold">Clientes con proyectos</h5>
                        <p class="card-text text-muted flex-grow-1">
                            Listado de clientes con sus proyectos asociados y presupuestos.
                        </p>
                        <a href="<?= APP_URL ?>/index.php?controller=reportes&action=clientes"
                            class="btn btn-primary mt-2" target="_blank">
                            <i class="fa-solid fa-download"></i> Descargar PDF
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="<?= APP_URL ?>/Assets/JS/bootstrap.bundle.min.js"></script>
    <script>
        function descargarDetalle() {
            const id = document.getElementById('selectProyecto').value;
            if (!id) {
                alert('Selecciona un proyecto primero.');
                return;
            }
            window.open('<?= APP_URL ?>/index.php?controller=reportes&action=proyectoDetalle&id=' + id, '_blank');
        }
    </script>
</body>

</html>