<?php

require_once __DIR__ . '/../../config/database.php';

class Repuesto
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connect();
    }

    public function obtenerTodos(): array
    {
        $sql = 'SELECT id_repuesto, nombre, descripcion, stock, precio, fecha_creacion FROM repuestos ORDER BY id_repuesto DESC';
        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = 'SELECT id_repuesto, nombre, descripcion, stock, precio FROM repuestos WHERE id_repuesto = :id LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function crear(array $datos): bool
    {
        $sql = 'INSERT INTO repuestos (nombre, descripcion, stock, precio) VALUES (:nombre, :descripcion, :stock, :precio)';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':stock' => $datos['stock'],
            ':precio' => $datos['precio'],
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE repuestos SET nombre = :nombre, descripcion = :descripcion, stock = :stock, precio = :precio WHERE id_repuesto = :id';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':stock' => $datos['stock'],
            ':precio' => $datos['precio'],
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = 'DELETE FROM repuestos WHERE id_repuesto = :id';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function existeNombre(string $nombre, ?int $idExcluir = null): bool
    {
        $sql = 'SELECT id_repuesto FROM repuestos WHERE nombre = :nombre';

        if ($idExcluir !== null) {
            $sql .= ' AND id_repuesto <> :id_excluir';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);

        if ($idExcluir !== null) {
            $statement->bindParam(':id_excluir', $idExcluir, PDO::PARAM_INT);
        }

        $statement->execute();

        return (bool) $statement->fetch();
    }

    public function obtenerBajoStock(int $limite = 5): array
    {
        $sql = 'SELECT id_repuesto, nombre, descripcion, stock, precio FROM repuestos WHERE stock <= :limite ORDER BY stock ASC, nombre ASC';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':limite', $limite, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function actualizarStock(int $id, int $stock): bool
    {
        $sql = 'UPDATE repuestos SET stock = :stock WHERE id_repuesto = :id';
        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id' => $id,
            ':stock' => $stock,
        ]);
    }

    public function contarTodos(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM repuestos';
        $statement = $this->connection->query($sql);
        $row = $statement->fetch();

        return (int) ($row['total'] ?? 0);
    }

    public function contarBajoStock(int $limite = 5): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM repuestos WHERE stock <= :limite';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':limite', $limite, PDO::PARAM_INT);
        $statement->execute();
        $row = $statement->fetch();

        return (int) ($row['total'] ?? 0);
    }
}
