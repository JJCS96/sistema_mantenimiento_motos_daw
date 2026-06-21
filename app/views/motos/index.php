<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Motos</h1>
        <p class="text-muted mb-0">Gestion de motos registradas y asociadas a clientes.</p>
    </div>
    <a href="index.php?controller=moto&action=crear" class="btn btn-primary">Nueva moto</a>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Anio</th>
                        <th>Color</th>
                        <th>Cliente</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($motos)): ?>
                        <?php foreach ($motos as $moto): ?>
                            <tr>
                                <td><?= htmlspecialchars($moto['placa']) ?></td>
                                <td><?= htmlspecialchars($moto['marca']) ?></td>
                                <td><?= htmlspecialchars($moto['modelo']) ?></td>
                                <td><?= htmlspecialchars((string) $moto['anio']) ?></td>
                                <td><?= htmlspecialchars($moto['color']) ?></td>
                                <td><?= htmlspecialchars($moto['nombres'] . ' ' . $moto['apellidos'] . ' - ' . $moto['cedula']) ?></td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="index.php?controller=moto&action=editar&id=<?= htmlspecialchars((string) $moto['id_moto']) ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <form method="POST" action="index.php?controller=moto&action=eliminar" onsubmit="return confirm('¿Desea eliminar esta moto?');">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $moto['id_moto']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay motos registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
