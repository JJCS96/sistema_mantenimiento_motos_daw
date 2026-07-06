<?php

require_once __DIR__ . '/../../config/database.php';

class Mantenimiento
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function obtenerTodos(): array
    {
        $sql = 'SELECT m.id_mantenimiento, m.id_cliente, m.id_moto, m.fecha, m.tipo_servicio, m.descripcion, m.costo_mano_obra, m.estado, m.total,
                       c.nombres, c.apellidos, c.cedula, mo.placa, mo.marca, mo.modelo
                FROM mantenimientos m
                INNER JOIN clientes c ON c.id_cliente = m.id_cliente
                INNER JOIN motos mo ON mo.id_moto = m.id_moto
                ORDER BY m.id_mantenimiento DESC';
        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = 'SELECT m.id_mantenimiento, m.id_cliente, m.id_moto, m.fecha, m.tipo_servicio, m.descripcion, m.costo_mano_obra, m.estado, m.total,
                       c.nombres, c.apellidos, c.cedula, mo.placa, mo.marca, mo.modelo
                FROM mantenimientos m
                INNER JOIN clientes c ON c.id_cliente = m.id_cliente
                INNER JOIN motos mo ON mo.id_moto = m.id_moto
                WHERE m.id_mantenimiento = :id
                LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function crear(array $datos): int|false
    {
        $sql = 'INSERT INTO mantenimientos (id_cliente, id_moto, fecha, tipo_servicio, descripcion, costo_mano_obra, estado, total)
                VALUES (:id_cliente, :id_moto, :fecha, :tipo_servicio, :descripcion, :costo_mano_obra, :estado, :total)';
        $statement = $this->connection->prepare($sql);
        $saved = $statement->execute([
            ':id_cliente' => $datos['id_cliente'],
            ':id_moto' => $datos['id_moto'],
            ':fecha' => $datos['fecha'],
            ':tipo_servicio' => $datos['tipo_servicio'],
            ':descripcion' => $datos['descripcion'],
            ':costo_mano_obra' => $datos['costo_mano_obra'],
            ':estado' => $datos['estado'],
            ':total' => $datos['total'] ?? 0,
        ]);

        return $saved ? (int) $this->connection->lastInsertId() : false;
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE mantenimientos
                SET id_cliente = :id_cliente,
                    id_moto = :id_moto,
                    fecha = :fecha,
                    tipo_servicio = :tipo_servicio,
                    descripcion = :descripcion,
                    costo_mano_obra = :costo_mano_obra,
                    estado = :estado
                WHERE id_mantenimiento = :id';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id' => $id,
            ':id_cliente' => $datos['id_cliente'],
            ':id_moto' => $datos['id_moto'],
            ':fecha' => $datos['fecha'],
            ':tipo_servicio' => $datos['tipo_servicio'],
            ':descripcion' => $datos['descripcion'],
            ':costo_mano_obra' => $datos['costo_mano_obra'],
            ':estado' => $datos['estado'],
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = 'DELETE FROM mantenimientos WHERE id_mantenimiento = :id';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function actualizarTotal(int $idMantenimiento, float $total): bool
    {
        $sql = 'UPDATE mantenimientos SET total = :total WHERE id_mantenimiento = :id';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id' => $idMantenimiento,
            ':total' => $total,
        ]);
    }

    public function obtenerPorEstado(string $estado): array
    {
        $sql = 'SELECT m.id_mantenimiento, m.fecha, m.tipo_servicio, m.estado, m.total,
                       c.nombres, c.apellidos, mo.placa, mo.marca, mo.modelo
                FROM mantenimientos m
                INNER JOIN clientes c ON c.id_cliente = m.id_cliente
                INNER JOIN motos mo ON mo.id_moto = m.id_moto
                WHERE m.estado = :estado
                ORDER BY m.fecha DESC, m.id_mantenimiento DESC';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':estado', $estado, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function obtenerPorCliente(int $idCliente): array
    {
        $sql = 'SELECT m.id_mantenimiento, m.fecha, m.tipo_servicio, m.estado, m.total,
                       c.nombres, c.apellidos, mo.placa, mo.marca, mo.modelo
                FROM mantenimientos m
                INNER JOIN clientes c ON c.id_cliente = m.id_cliente
                INNER JOIN motos mo ON mo.id_moto = m.id_moto
                WHERE m.id_cliente = :id_cliente
                ORDER BY m.fecha DESC, m.id_mantenimiento DESC';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id_cliente', $idCliente, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function contarTodos(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM mantenimientos';
        $statement = $this->connection->query($sql);
        $row = $statement->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function contarPorEstado(string $estado): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM mantenimientos WHERE estado = :estado';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':estado', $estado, PDO::PARAM_STR);
        $statement->execute();
        $row = $statement->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function obtenerUltimos(int $limite = 5): array
    {
        $limite = max(1, $limite);
        $sql = 'SELECT m.id_mantenimiento, m.fecha, m.tipo_servicio, m.estado, m.total,
                       c.nombres, c.apellidos, mo.placa, mo.marca, mo.modelo
                FROM mantenimientos m
                INNER JOIN clientes c ON c.id_cliente = m.id_cliente
                INNER JOIN motos mo ON mo.id_moto = m.id_moto
                ORDER BY m.id_mantenimiento DESC
                LIMIT ' . $limite;
        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }

    public function obtenerPorRangoFechas(string $fechaInicio, string $fechaFin): array
    {
        $sql = 'SELECT m.id_mantenimiento, m.fecha, m.tipo_servicio, m.estado, m.total,
                       c.nombres, c.apellidos, mo.placa, mo.marca, mo.modelo
                FROM mantenimientos m
                INNER JOIN clientes c ON c.id_cliente = m.id_cliente
                INNER JOIN motos mo ON mo.id_moto = m.id_moto
                WHERE m.fecha BETWEEN :fecha_inicio AND :fecha_fin
                ORDER BY m.fecha DESC, m.id_mantenimiento DESC';
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':fecha_inicio' => $fechaInicio,
            ':fecha_fin' => $fechaFin,
        ]);

        return $statement->fetchAll();
    }

    public function calcularTotalGeneral(): float
    {
        $sql = 'SELECT COALESCE(SUM(total), 0) AS total_general FROM mantenimientos';
        $statement = $this->connection->query($sql);
        $row = $statement->fetch();

        return (float) ($row['total_general'] ?? 0);
    }
}
