CREATE DATABASE IF NOT EXISTS segundo_parcial_daw_motos CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE segundo_parcial_daw_motos;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL DEFAULT 'Administrador',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Para fines academicos se deja la contrasena en texto plano.
-- En un sistema real se debe usar password_hash y password_verify.
INSERT INTO usuarios (nombre, correo, password, rol)
SELECT 'Administrador', 'admin@gmail.com', '123456', 'Administrador'
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios WHERE correo = 'admin@gmail.com'
);

CREATE TABLE IF NOT EXISTS clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(10) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    direccion VARCHAR(150) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS motos (
    id_moto INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    placa VARCHAR(10) NOT NULL UNIQUE,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    anio INT NOT NULL,
    color VARCHAR(30) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_motos_clientes FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
