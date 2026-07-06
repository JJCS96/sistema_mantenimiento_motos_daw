<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Repuestos</h1>
        <p class="text-muted mb-0">Gestion de repuestos disponibles para los mantenimientos.</p>
    </div>
    <a href="index.php?controller=repuesto&action=crear" class="btn btn-primary">Nuevo repuesto</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($repuestos)): ?>
                        <?php foreach ($repuestos as $repuesto): ?>
                            <tr>
                                <td><?= htmlspecialchars($repuesto['nombre']) ?></td>
                                <td><?= htmlspecialchars($repuesto['descripcion']) ?></td>
                                <td>
                                    <?= htmlspecialchars((string) $repuesto['stock']) ?>
                                    <?php if ((int) $repuesto['stock'] <= 5): ?>
                                        <span class="badge text-bg-warning ms-2">Bajo stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>$<?= htmlspecialchars(number_format((float) $repuesto['precio'], 2)) ?></td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="index.php?controller=repuesto&action=editar&id=<?= htmlspecialchars((string) $repuesto['id_repuesto']) ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="#" onclick="return confirmarEliminacion('index.php?controller=repuesto&action=eliminar&id=<?= htmlspecialchars((string) $repuesto['id_repuesto']) ?>');" class="btn btn-sm btn-danger">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay repuestos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
