<?php

require_once __DIR__ . '/../../Config/Config.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/Assets/CSS/bootstrap.min.css">
    <script src="<?= APP_URL ?>/Assets/JS/fontawesome_icons_algoasi.js"></script>
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="card shadow" style="width: 420px;">
        <div class="card-header bg-primary text-white text-center py-4">
            <h4 class="mb-0 fw-bold"><i class="fa-solid fa-lock"></i> <?= APP_NAME ?></h4>
            <p class="mb-0 small opacity-75"><i class="fa-solid fa-briefcase"></i> Sistema de Gestión de Proyectos</p>
        </div>

        <div class="card-body p-4">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= APP_URL ?>/index.php?controller=auth&action=autenticar" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold"><i class="fa-solid fa-envelope"></i> Correo electrónico</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="correo@ejemplo.com"
                        required
                        autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold"><i class="fa-solid fa-key"></i> Contraseña</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Iniciar sesión
                </button>
            </form>

        </div>
    </div>

    <script src="<?= APP_URL ?>/Assets/JS/bootstrap.bundle.min.js"></script>
</body>

</html>