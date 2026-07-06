# Sistema Web de Gestión de Mantenimiento de Motos

## Descripción general

Este sistema permite gestionar la información principal de un taller de motos. Incluye administración de clientes, motos, repuestos, mantenimientos, detalle de repuestos usados y reportes básicos para apoyar el control del taller.

## Objetivo

Desarrollar una aplicación web en PHP y MySQL usando MVC, operaciones CRUD y validaciones para organizar la gestión de mantenimiento de motos de forma clara y funcional.

## Tecnologías usadas

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Bootstrap
- SweetAlert2
- PDO
- XAMPP

## Requisitos

- XAMPP
- PHP 8 o superior
- MySQL o MariaDB
- Navegador web

## Instalación

1. Copiar el proyecto en:

```text
C:\xampp\htdocs\sistema_mantenimiento_motos_daw
```

2. Encender Apache y MySQL en XAMPP.
3. Importar `database.sql` desde phpMyAdmin.
4. Revisar `config/database.php`.
5. Abrir en el navegador:

```text
http://localhost/sistema_mantenimiento_motos_daw/public/
```

## Despliegue online

- El proyecto puede subirse a un hosting PHP + MySQL.
- Para la entrega se recomienda InfinityFree o un hosting similar.
- Se debe crear una base MySQL en el hosting.
- Se debe importar `database.sql`.
- Se debe editar `config/database.php` con los datos reales del hosting.
- Ver mas detalles en `DESPLIEGUE.md`.

## Usuario de prueba

- correo: `admin@gmail.com`
- contraseña: `123456`

Nota: por fines académicos la contraseña se mantiene simple. En un sistema real debe usarse `password_hash` y `password_verify`.

## Estructura MVC

- Modelo: gestiona la conexión y las consultas a la base de datos.
- Vista: muestra formularios, tablas, botones y la interfaz del sistema.
- Controlador: recibe acciones del usuario y coordina modelo y vista.

Estructura general:

```text
sistema_mantenimiento_motos_daw/
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
├── config/
├── public/
├── database.sql
├── README.md
├── SUSTENTACION.md
└── PRUEBAS_MANUALES.md
```

## Funcionalidades principales

- Login
- Dashboard
- CRUD clientes
- CRUD motos
- CRUD repuestos
- CRUD mantenimientos
- Detalle de mantenimiento
- Reportes
- SweetAlert2
- Validaciones

## Base de datos

La base de datos principal es `segundo_parcial_daw_motos`.

### Tablas principales

- `usuarios`
- `clientes`
- `motos`
- `repuestos`
- `mantenimientos`
- `detalle_mantenimiento`

### Relaciones

- un cliente tiene varias motos
- una moto tiene varios mantenimientos
- un mantenimiento puede usar varios repuestos
- los repuestos se descuentan del stock al usarse

## Validaciones

### Frontend

- campos obligatorios
- validaciones JavaScript
- correos
- fechas
- cantidades
- stock
- precios

### Backend

- campos vacíos
- duplicados
- relaciones existentes
- datos numéricos
- fechas
- cantidades
- stock
- estados permitidos

## Reportes

- por rango de fechas
- por cliente
- por estado
- repuestos con bajo stock

## Dashboard

El dashboard muestra:

- total de clientes
- total de motos
- total de repuestos
- total de mantenimientos
- mantenimientos pendientes, en proceso y finalizados
- repuestos con bajo stock
- total generado por mantenimientos
- últimos 5 mantenimientos registrados

## Instrucciones de uso

1. Iniciar sesión con el usuario de prueba.
2. Registrar o revisar clientes.
3. Registrar motos asociadas a clientes.
4. Registrar repuestos.
5. Registrar mantenimientos usando repuestos.
6. Revisar el detalle del mantenimiento.
7. Consultar métricas en el dashboard.
8. Consultar reportes.

## Sustentación

Puntos recomendados para explicar:

- problema que resuelve el sistema
- estructura del proyecto
- funcionamiento del CRUD
- conexión segura con PDO
- patrón MVC
- validaciones frontend y backend
- demostración práctica del sistema

Para apoyar la exposición se incluyen también:

- `SUSTENTACION.md`
- `PRUEBAS_MANUALES.md`

## Datos de prueba incluidos

El archivo `database.sql` incluye:

- usuario administrador
- 2 clientes
- 2 motos
- 3 repuestos
- 1 mantenimiento de ejemplo
- detalles de repuestos usados

## Integrantes

Espacio para completar con los nombres del grupo:

- Integrante 1: ____________________
- Integrante 2: ____________________
- Integrante 3: ____________________

## Estado final del proyecto

Proyecto preparado para entrega académica, ejecución local en XAMPP y sustentación.
