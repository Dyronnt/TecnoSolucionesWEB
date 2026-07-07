<?php

/** @var array $proyectos */
/** @var string $totalProyectos */
/** @var string $totalClientes */
/** @var string $totalUsuarios */



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
    <title>Dashboard - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/Assets/CSS/bootstrap.min.css">
    <script src="<?= APP_URL ?>/Assets/JS/fontawesome_icons_algoasi.js"></script>
</head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= APP_URL ?>/index.php">
                <?= APP_NAME ?>
            </a>
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
        <h4 class="mb-4">
            Bienvenido, <?= $_SESSION['usuario_nombre'] ?>
            <i class="fa-solid fa-ghost"></i>
        </h4>

        <div class="row g-4">
            <!-- Card Clientes -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded p-3 fs-4">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Clientes</h6>
                            <h3 class="mb-0 fw-bold"><?= $totalClientes ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="<?= APP_URL ?>/index.php?controller=clientes&action=index" class="text-primary small">
                            Ver clientes
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Proyectos -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-success text-white rounded p-3 fs-4">
                            <i class="fa-solid fa-folder"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Proyectos activos</h6>
                            <h3 class="mb-0 fw-bold"><?= $totalProyectos ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="<?= APP_URL ?>/index.php?controller=proyectos&action=index" class="text-success small">
                            Ver proyectos
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Usuarios (solo admin) -->
            <?php if ($_SESSION['usuario_rol'] === 'administrador'): ?>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning text-white rounded p-3 fs-4">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Usuarios</h6>
                                <h3 class="mb-0 fw-bold"><?= $totalUsuarios ?></h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="<?= APP_URL ?>/index.php?controller=usuarios&action=index" class="text-warning small">
                                Ver usuarios
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>


            <?php endif; ?>
        </div>

        <!-- SECCIÓN REPORTES (solo admin) -->
        <?php if ($_SESSION['usuario_rol'] === 'administrador'): ?>
            <hr class="my-4">
            <h5 class="fw-bold mb-3">📄 Reportes</h5>
            <div class="row g-3">

                <!-- Reporte todos los proyectos -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold">Todos los proyectos</h6>
                            <p class="text-muted small mb-3">Listado completo con estado, fechas y cliente asignado.</p>
                            <a href="<?= APP_URL ?>/index.php?controller=reportes&action=proyectos"
                                class="btn btn-sm btn-outline-primary w-100" target="_blank">
                                <i class="fa-solid fa-download"></i> Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Reporte detalle de proyecto -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold">Detalle de proyecto</h6>
                            <p class="text-muted small mb-2">Informe detallado de un proyecto específico.</p>
                            <select class="form-select form-select-sm mb-2" id="selectProyecto">
                                <option value="">— Selecciona un proyecto —</option>
                                <?php foreach ($proyectos as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-sm btn-outline-primary w-100" onclick="descargarDetalle()">
                                <i class="fa-solid fa-download"></i> Descargar PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Reporte clientes -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold">Clientes con proyectos</h6>
                            <p class="text-muted small mb-3">Listado de clientes con sus proyectos y presupuestos.</p>
                            <a href="<?= APP_URL ?>/index.php?controller=reportes&action=clientes"
                                class="btn btn-sm btn-outline-primary w-100" target="_blank">
                                <i class="fa-solid fa-download"></i> Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>

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