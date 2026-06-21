<?php

require_once __DIR__ . '/../../config/database.php';

class Usuario
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function findByCredentials(string $correo, string $password): array|false
    {
        // Para fines academicos se compara texto plano. En produccion se debe usar password_hash.
        $sql = 'SELECT id_usuario, nombre, correo, rol FROM usuarios WHERE correo = :correo AND password = :password LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':correo', $correo, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
