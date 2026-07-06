<?php

require_once __DIR__ . '/../../config/database.php';

class Moto
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function obtenerTodos(): array
    {
        $sql = 'SELECT m.id_moto, m.id_cliente, m.placa, m.marca, m.modelo, m.anio, m.color, c.cedula, c.nombres, c.apellidos
                FROM motos m
                INNER JOIN clientes c ON c.id_cliente = m.id_cliente
                ORDER BY m.id_moto DESC';
        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = 'SELECT id_moto, id_cliente, placa, marca, modelo, anio, color FROM motos WHERE id_moto = :id LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function crear(array $datos): bool
    {
        $sql = 'INSERT INTO motos (id_cliente, placa, marca, modelo, anio, color) VALUES (:id_cliente, :placa, :marca, :modelo, :anio, :color)';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id_cliente' => $datos['id_cliente'],
            ':placa' => $datos['placa'],
            ':marca' => $datos['marca'],
            ':modelo' => $datos['modelo'],
            ':anio' => $datos['anio'],
            ':color' => $datos['color'],
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE motos SET id_cliente = :id_cliente, placa = :placa, marca = :marca, modelo = :modelo, anio = :anio, color = :color WHERE id_moto = :id';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id' => $id,
            ':id_cliente' => $datos['id_cliente'],
            ':placa' => $datos['placa'],
            ':marca' => $datos['marca'],
            ':modelo' => $datos['modelo'],
            ':anio' => $datos['anio'],
            ':color' => $datos['color'],
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = 'DELETE FROM motos WHERE id_moto = :id';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function existePlaca(string $placa, ?int $idExcluir = null): bool
    {
        $sql = 'SELECT id_moto FROM motos WHERE placa = :placa';

        if ($idExcluir !== null) {
            $sql .= ' AND id_moto <> :id_excluir';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':placa', $placa, PDO::PARAM_STR);

        if ($idExcluir !== null) {
            $statement->bindParam(':id_excluir', $idExcluir, PDO::PARAM_INT);
        }

        $statement->execute();

        return (bool) $statement->fetch();
    }

    public function obtenerPorCliente(int $idCliente): array
    {
        $sql = 'SELECT id_moto, id_cliente, placa, marca, modelo, anio, color FROM motos WHERE id_cliente = :id_cliente ORDER BY id_moto DESC';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id_cliente', $idCliente, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function contarTodos(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM motos';
        $statement = $this->connection->query($sql);
        $row = $statement->fetch();

        return (int) ($row['total'] ?? 0);
    }
}
