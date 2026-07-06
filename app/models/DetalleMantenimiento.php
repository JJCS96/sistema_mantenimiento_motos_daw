<?php

require_once __DIR__ . '/../../config/database.php';

class DetalleMantenimiento
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function obtenerPorMantenimiento(int $idMantenimiento): array
    {
        $sql = 'SELECT d.id_detalle, d.id_mantenimiento, d.id_repuesto, d.cantidad, d.precio_unitario, d.subtotal, r.nombre
                FROM detalle_mantenimiento d
                INNER JOIN repuestos r ON r.id_repuesto = d.id_repuesto
                WHERE d.id_mantenimiento = :id_mantenimiento
                ORDER BY d.id_detalle ASC';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id_mantenimiento', $idMantenimiento, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function agregarDetalle(array $datos): bool
    {
        $sql = 'INSERT INTO detalle_mantenimiento (id_mantenimiento, id_repuesto, cantidad, precio_unitario, subtotal)
                VALUES (:id_mantenimiento, :id_repuesto, :cantidad, :precio_unitario, :subtotal)';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id_mantenimiento' => $datos['id_mantenimiento'],
            ':id_repuesto' => $datos['id_repuesto'],
            ':cantidad' => $datos['cantidad'],
            ':precio_unitario' => $datos['precio_unitario'],
            ':subtotal' => $datos['subtotal'],
        ]);
    }

    public function eliminarPorMantenimiento(int $idMantenimiento): bool
    {
        $sql = 'DELETE FROM detalle_mantenimiento WHERE id_mantenimiento = :id_mantenimiento';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id_mantenimiento', $idMantenimiento, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function calcularTotal(int $idMantenimiento): float
    {
        $sql = 'SELECT COALESCE(SUM(subtotal), 0) AS total_repuestos FROM detalle_mantenimiento WHERE id_mantenimiento = :id_mantenimiento';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id_mantenimiento', $idMantenimiento, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch();

        return (float) ($row['total_repuestos'] ?? 0);
    }
}
