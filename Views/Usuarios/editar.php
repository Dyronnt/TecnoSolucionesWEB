<?php
require_once __DIR__ . '/../../Config/Config.php';

/** @var array $usuario */

/* La linea de arriba es para que el editor (vs code)no muestre errores,
pues el array usuario viene desde el controlador,
y al no estar declarado aqui, pues sale error cuando no lo hay. */

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . APP_URL . '/index.php?controller=auth&action=login');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'administrador') {
    header('Location: ' . APP_URL . '/index.php?controller=clientes&action=index');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - <?= APP_NAME ?></title>
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
                            <a class="nav-link active" href="<?= APP_URL ?>/index.php?controller=usuarios&action=index">Usuarios</a>
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
                            <li><hr class="dropdown-divider"></li>
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
            <h4 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square"></i> Editar Usuario</h4>
            <a href="<?= APP_URL ?>/index.php?controller=usuarios&action=index" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <!-- ALERTA ERROR -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="<?= APP_URL ?>/index.php?controller=usuarios&action=actualizar" method="POST">
                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre"
                                value="<?= htmlspecialchars($usuario['nombre']) ?>" required maxlength="100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email"
                                value="<?= htmlspecialchars($usuario['email']) ?>" required maxlength="150">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nueva contraseña</label>
                            <input type="password" class="form-control" name="password" minlength="6">
                            <div class="form-text">Dejar en blanco para mantener la contraseña actual.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control" name="password_confirm" minlength="6">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" name="rol" required
                                <?= $usuario['id'] === $_SESSION['usuario_id'] ? 'disabled' : '' ?>>
                                <option value="empleado" <?= $usuario['rol'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
                                <option value="administrador" <?= $usuario['rol'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                            <?php if ($usuario['id'] === $_SESSION['usuario_id']): ?>
                                <input type="hidden" name="rol" value="<?= $usuario['rol'] ?>">
                                <div class="form-text text-warning">No puedes cambiar tu propio rol.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= APP_URL ?>/index.php?controller=usuarios&action=index" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Actualizar Usuario</button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <script src="<?= APP_URL ?>/Assets/JS/bootstrap.bundle.min.js"></script>
</body>

</html>