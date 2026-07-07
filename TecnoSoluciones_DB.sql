-- BASE DE DATOS: TecnoSolucionesWEB (utf8mb4_general_ci)


CREATE DATABASE IF NOT EXISTS tecnosolucionesweb
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tecnosolucionesweb;

-- TABLA: USUARIOS
CREATE TABLE USUARIOS (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100)        NOT NULL,
    email           VARCHAR(150)        NOT NULL UNIQUE,
    password        VARCHAR(255)        NOT NULL,
    rol             ENUM('administrador', 'empleado') NOT NULL DEFAULT 'empleado',
    fecha_creacion  TIMESTAMP           DEFAULT CURRENT_TIMESTAMP
);

-- TABLA: CLIENTES

CREATE TABLE CLIENTES (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(150)    NOT NULL,
    ruc             VARCHAR(20)     NOT NULL UNIQUE,
    telefono        VARCHAR(20),
    email           VARCHAR(150),
    direccion       VARCHAR(255),
    fecha_registro  DATE            NOT NULL DEFAULT (CURRENT_DATE)
);


-- TABLA: PROYECTOS

CREATE TABLE PROYECTOS (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(150)    NOT NULL,
    descripcion     TEXT,
    fecha_inicio    DATE            NOT NULL,
    fecha_fin       DATE,
    estado          ENUM('pendiente', 'en curso', 'completado', 'cancelado') NOT NULL DEFAULT 'pendiente',
    presupuesto     DECIMAL(10,2),
    cliente_id      INT             NOT NULL,
    fecha_creacion  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_proyecto_cliente
        FOREIGN KEY (cliente_id)
        REFERENCES CLIENTES(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


-- DATOS DE PRUEBA


-- Usuario administrador (password: t3cn0_s0luc10n3s)
INSERT INTO USUARIOS (nombre, email, password, rol) VALUES
('Administrador', 'admin@tecnosoluciones.com', '$2y$10$YUnx6BHBDXyLkBYdjHjvlu2cZsAkdGZqnMuGuZ5On3Bgl1NPeUXA2', 'administrador'),

('Dyron Torres', 'dyron@tecnosoluciones.com', '$2y$10$9ZgOn41li38JC9JgrstJau4cCQtB/yjiSQNSrpbeMeeIUpa03tFxO', 'empleado');
-- Password: Dyronlefleur


-- Clientes de prueba
INSERT INTO CLIENTES (nombre, ruc, telefono, email, direccion) VALUES
('Empresa Alpha S.A.', '20123456781', '01-4521234', 'contacto@alpha.com', 'Av. Lima 123, Lima'),
('Corporación Beta', '20987654321', '01-7654321', 'info@beta.com', 'Jr. Miraflores 456, Lima'),
('Soluciones Gamma S.R.L.', '20456789123', '01-3219876', 'hola@gamma.com', 'Calle Surco 789, Lima');

-- Proyectos de prueba
INSERT INTO PROYECTOS (nombre, descripcion, fecha_inicio, fecha_fin, estado, presupuesto, cliente_id) VALUES
('Sistema de Facturación', 'Desarrollo de sistema de facturación electrónica', '2025-01-10', '2025-04-10', 'completado', 15000.00, 1),
('App Móvil de Ventas', 'Aplicación Android/iOS para gestión de ventas', '2025-03-01', '2025-08-01', 'en curso', 28000.00, 2),
('Portal Web Corporativo', 'Rediseño completo del portal web institucional', '2025-05-15', NULL, 'pendiente', 9500.00, 3);