# Sistema Web para Gestion de Mantenimiento de Motos

Proyecto del segundo parcial de DAW desarrollado con PHP puro, MySQL, HTML, CSS, JavaScript y estructura MVC.

## Estado actual

FASE 2 completada: CRUD de clientes agregado.

## Tecnologias usadas

- PHP puro
- MySQL
- PDO
- HTML5
- CSS3
- JavaScript
- Bootstrap 5

## Estructura MVC usada

```text
sistema_mantenimiento_motos_daw/
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
├── config/
├── public/
├── database.sql
└── README.md
```

## Que incluye esta fase

- Front controller en `public/index.php`
- Conexion a MySQL usando PDO
- Login basico con sesion
- Proteccion de rutas internas
- Dashboard inicial
- CRUD completo de clientes
- Modulos base para motos, repuestos, mantenimientos y reportes

## Requisitos

- XAMPP o un entorno con Apache y MySQL
- PHP 8 o superior
- MySQL o MariaDB

## Como ejecutar en XAMPP

1. Copie la carpeta `sistema_mantenimiento_motos_daw` dentro de `htdocs` o configure un virtual host.
2. Inicie Apache y MySQL desde XAMPP.
3. Cree la base de datos importando el archivo `database.sql`.
4. Revise `config/database.php` y confirme que el usuario y la clave de MySQL sean correctos.
5. Abra en el navegador la ruta del proyecto, por ejemplo:

```text
http://localhost/sistema_mantenimiento_motos_daw/public/
```

Ruta oficial en XAMPP:

```text
C:\xampp\htdocs\sistema_mantenimiento_motos_daw
```

## Como importar database.sql

1. Abra phpMyAdmin.
2. Seleccione la opcion Importar.
3. Elija el archivo `C:\xampp\htdocs\sistema_mantenimiento_motos_daw\database.sql`.
4. Ejecute la importacion.

## Usuario de prueba

- Correo: `admin@gmail.com`
- Contrasena: `123456`

Nota: en un proyecto real la contrasena no debe guardarse en texto plano. Se debe usar `password_hash`.

## Modulo de clientes

El sistema ya permite:

- listar clientes
- registrar clientes
- editar clientes
- eliminar clientes
- validar cedula, telefono y correo tanto en frontend como en backend

## Descripcion breve del patron MVC

- Modelo: gestiona acceso a datos.
- Vista: muestra la interfaz al usuario.
- Controlador: recibe la peticion y coordina modelo y vista.

## Siguiente etapa

En la siguiente fase se desarrollara el CRUD de motos.
