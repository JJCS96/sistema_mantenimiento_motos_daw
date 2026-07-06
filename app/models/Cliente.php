<?php

require_once __DIR__ . '/../../config/database.php';

class Cliente
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function obtenerTodos(): array
    {
        $sql = 'SELECT id_cliente, cedula, nombres, apellidos, telefono, correo, direccion, fecha_creacion FROM clientes ORDER BY id_cliente DESC';
        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = 'SELECT id_cliente, cedula, nombres, apellidos, telefono, correo, direccion FROM clientes WHERE id_cliente = :id LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function crear(array $datos): bool
    {
        $sql = 'INSERT INTO clientes (cedula, nombres, apellidos, telefono, correo, direccion) VALUES (:cedula, :nombres, :apellidos, :telefono, :correo, :direccion)';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':cedula' => $datos['cedula'],
            ':nombres' => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':telefono' => $datos['telefono'],
            ':correo' => $datos['correo'],
            ':direccion' => $datos['direccion'],
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE clientes SET cedula = :cedula, nombres = :nombres, apellidos = :apellidos, telefono = :telefono, correo = :correo, direccion = :direccion WHERE id_cliente = :id';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id' => $id,
            ':cedula' => $datos['cedula'],
            ':nombres' => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':telefono' => $datos['telefono'],
            ':correo' => $datos['correo'],
            ':direccion' => $datos['direccion'],
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = 'DELETE FROM clientes WHERE id_cliente = :id';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function existeCedula(string $cedula, ?int $idExcluir = null): bool
    {
        $sql = 'SELECT id_cliente FROM clientes WHERE cedula = :cedula';

        if ($idExcluir !== null) {
            $sql .= ' AND id_cliente <> :id_excluir';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':cedula', $cedula, PDO::PARAM_STR);

        if ($idExcluir !== null) {
            $statement->bindParam(':id_excluir', $idExcluir, PDO::PARAM_INT);
        }

        $statement->execute();

        return (bool) $statement->fetch();
    }

    public function existeCorreo(string $correo, ?int $idExcluir = null): bool
    {
        $sql = 'SELECT id_cliente FROM clientes WHERE correo = :correo';

        if ($idExcluir !== null) {
            $sql .= ' AND id_cliente <> :id_excluir';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':correo', $correo, PDO::PARAM_STR);

        if ($idExcluir !== null) {
            $statement->bindParam(':id_excluir', $idExcluir, PDO::PARAM_INT);
        }

        $statement->execute();

        return (bool) $statement->fetch();
    }

    public function contarTodos(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM clientes';
        $statement = $this->connection->query($sql);
        $row = $statement->fetch();

        return (int) ($row['total'] ?? 0);
    }
}
