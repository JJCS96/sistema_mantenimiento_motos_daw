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

CREATE TABLE IF NOT EXISTS repuestos (
    id_repuesto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(200) NOT NULL,
    stock INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mantenimientos (
    id_mantenimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_moto INT NOT NULL,
    fecha DATE NOT NULL,
    tipo_servicio VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    costo_mano_obra DECIMAL(10, 2) NOT NULL,
    estado ENUM('Pendiente','En proceso','Finalizado') NOT NULL DEFAULT 'Pendiente',
    total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mantenimientos_clientes FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_mantenimientos_motos FOREIGN KEY (id_moto) REFERENCES motos(id_moto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS detalle_mantenimiento (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_mantenimiento INT NOT NULL,
    id_repuesto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_detalle_mantenimiento_mantenimiento FOREIGN KEY (id_mantenimiento) REFERENCES mantenimientos(id_mantenimiento)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_detalle_mantenimiento_repuesto FOREIGN KEY (id_repuesto) REFERENCES repuestos(id_repuesto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

INSERT INTO clientes (cedula, nombres, apellidos, telefono, correo, direccion)
SELECT '0102030405', 'Juan', 'Perez', '0991112233', 'juan.perez@gmail.com', 'Av. Principal y Calle 1'
WHERE NOT EXISTS (
    SELECT 1 FROM clientes WHERE cedula = '0102030405'
);

INSERT INTO clientes (cedula, nombres, apellidos, telefono, correo, direccion)
SELECT '0912345678', 'Maria', 'Lopez', '0987654321', 'maria.lopez@gmail.com', 'Cdla. Central Mz 10'
WHERE NOT EXISTS (
    SELECT 1 FROM clientes WHERE cedula = '0912345678'
);

INSERT INTO motos (id_cliente, placa, marca, modelo, anio, color)
SELECT c.id_cliente, 'MOTO001', 'Yamaha', 'FZ', 2022, 'Negro'
FROM clientes c
WHERE c.cedula = '0102030405'
  AND NOT EXISTS (
      SELECT 1 FROM motos WHERE placa = 'MOTO001'
  );

INSERT INTO motos (id_cliente, placa, marca, modelo, anio, color)
SELECT c.id_cliente, 'MOTO002', 'Honda', 'CB190R', 2021, 'Rojo'
FROM clientes c
WHERE c.cedula = '0912345678'
  AND NOT EXISTS (
      SELECT 1 FROM motos WHERE placa = 'MOTO002'
  );

INSERT INTO repuestos (nombre, descripcion, stock, precio)
SELECT 'Aceite 4T', 'Aceite para motor de 4 tiempos', 8, 12.50
WHERE NOT EXISTS (
    SELECT 1 FROM repuestos WHERE nombre = 'Aceite 4T'
);

INSERT INTO repuestos (nombre, descripcion, stock, precio)
SELECT 'Filtro de aceite', 'Filtro para cambio de aceite de moto', 4, 7.00
WHERE NOT EXISTS (
    SELECT 1 FROM repuestos WHERE nombre = 'Filtro de aceite'
);

INSERT INTO repuestos (nombre, descripcion, stock, precio)
SELECT 'Pastillas de freno', 'Juego de pastillas de freno delanteras', 12, 18.75
WHERE NOT EXISTS (
    SELECT 1 FROM repuestos WHERE nombre = 'Pastillas de freno'
);

INSERT INTO mantenimientos (id_cliente, id_moto, fecha, tipo_servicio, descripcion, costo_mano_obra, estado, total)
SELECT c.id_cliente, m.id_moto, '2026-06-01', 'Cambio de aceite', 'Cambio de aceite y filtro con revision general.', 15.00, 'Finalizado', 47.00
FROM clientes c
INNER JOIN motos m ON m.id_cliente = c.id_cliente
WHERE c.cedula = '0102030405'
  AND m.placa = 'MOTO001'
  AND NOT EXISTS (
      SELECT 1
      FROM mantenimientos man
      WHERE man.id_moto = m.id_moto
        AND man.fecha = '2026-06-01'
        AND man.tipo_servicio = 'Cambio de aceite'
  );

INSERT INTO detalle_mantenimiento (id_mantenimiento, id_repuesto, cantidad, precio_unitario, subtotal)
SELECT man.id_mantenimiento, r.id_repuesto, 2, 12.50, 25.00
FROM mantenimientos man
INNER JOIN motos m ON m.id_moto = man.id_moto
INNER JOIN repuestos r ON r.nombre = 'Aceite 4T'
WHERE m.placa = 'MOTO001'
  AND man.fecha = '2026-06-01'
  AND man.tipo_servicio = 'Cambio de aceite'
  AND NOT EXISTS (
      SELECT 1 FROM detalle_mantenimiento d
      WHERE d.id_mantenimiento = man.id_mantenimiento
        AND d.id_repuesto = r.id_repuesto
  );

INSERT INTO detalle_mantenimiento (id_mantenimiento, id_repuesto, cantidad, precio_unitario, subtotal)
SELECT man.id_mantenimiento, r.id_repuesto, 1, 7.00, 7.00
FROM mantenimientos man
INNER JOIN motos m ON m.id_moto = man.id_moto
INNER JOIN repuestos r ON r.nombre = 'Filtro de aceite'
WHERE m.placa = 'MOTO001'
  AND man.fecha = '2026-06-01'
  AND man.tipo_servicio = 'Cambio de aceite'
  AND NOT EXISTS (
      SELECT 1 FROM detalle_mantenimiento d
      WHERE d.id_mantenimiento = man.id_mantenimiento
        AND d.id_repuesto = r.id_repuesto
  );
