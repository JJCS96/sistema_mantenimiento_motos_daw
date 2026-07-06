<?php

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        // Configuracion local XAMPP.
        // Para InfinityFree o un hosting similar, reemplace estos datos
        // por los valores entregados en el panel del hosting:
        // host, nombre de base de datos, usuario y contrasena.
        $host = 'sql301.infinityfree.com';
        $database = 'if0_42345354_segundo_parcial_daw_motos';
        $username = 'if0_42345354';
        $password = 'B4RC3L0Na16';
        $charset = 'utf8mb4';
        $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";

        try {
            self::$connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            return self::$connection;
        } catch (PDOException $exception) {
            die('Error de conexion a la base de datos: ' . $exception->getMessage());
        }
    }
}
