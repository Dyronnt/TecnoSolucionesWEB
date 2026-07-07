<?php

// Config.php - Configuración general del sistema

define('APP_NAME',      'TecnoSoluciones S.A.');
define('APP_VERSION',   '1.0.0');
define('APP_URL',       'http://localhost/TecnoSolucionesWEB');
date_default_timezone_set('America/Lima'); // Esto es para configurar la zona horaria del servidor

// Rutas base
define('BASE_PATH',     __DIR__ . '/../');
define('VIEWS_PATH',    BASE_PATH . 'Views/');
define('HELPERS_PATH',  BASE_PATH . 'Helpers/');

// Configuración de sesión
define('SESSION_NAME',  'tecnosoluciones_session');
define('SESSION_TIME',  3600); // 1 hora en segundos


?>