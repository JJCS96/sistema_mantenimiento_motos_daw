<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Mantenimientos</h1>
        <p class="text-muted mb-0">Gestion de mantenimientos y repuestos usados.</p>
    </div>
    <a href="index.php?controller=mantenimiento&action=crear" class="btn btn-primary">Nuevo mantenimiento</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Moto</th>
                        <th>Tipo de servicio</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($mantenimientos)): ?>
                        <?php foreach ($mantenimientos as $mantenimiento): ?>
                            <tr>
                                <td><?= htmlspecialchars($mantenimiento['fecha']) ?></td>
                                <td><?= htmlspecialchars($mantenimiento['nombres'] . ' ' . $mantenimiento['apellidos']) ?></td>
                                <td><?= htmlspecialchars($mantenimiento['placa'] . ' - ' . $mantenimiento['marca'] . ' ' . $mantenimiento['modelo']) ?></td>
                                <td><?= htmlspecialchars($mantenimiento['tipo_servicio']) ?></td>
                                <td><?= htmlspecialchars($mantenimiento['estado']) ?></td>
                                <td>$<?= htmlspecialchars(number_format((float) $mantenimiento['total'], 2)) ?></td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2 flex-wrap justify-content-center">
                                        <a href="index.php?controller=mantenimiento&action=detalle&id=<?= htmlspecialchars((string) $mantenimiento['id_mantenimiento']) ?>" class="btn btn-sm btn-info text-white">Ver detalle</a>
                                        <a href="index.php?controller=mantenimiento&action=editar&id=<?= htmlspecialchars((string) $mantenimiento['id_mantenimiento']) ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="#" onclick="return confirmarEliminacion('index.php?controller=mantenimiento&action=eliminar&id=<?= htmlspecialchars((string) $mantenimiento['id_mantenimiento']) ?>');" class="btn btn-sm btn-danger">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay mantenimientos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
