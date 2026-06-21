<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Clientes</h1>
        <p class="text-muted mb-0">Gestion de clientes registrados en el taller.</p>
    </div>
    <a href="index.php?controller=cliente&action=crear" class="btn btn-primary">Nuevo cliente</a>
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
                        <th>Cedula</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Telefono</th>
                        <th>Correo</th>
                        <th>Direccion</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)): ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= htmlspecialchars($cliente['cedula']) ?></td>
                                <td><?= htmlspecialchars($cliente['nombres']) ?></td>
                                <td><?= htmlspecialchars($cliente['apellidos']) ?></td>
                                <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                                <td><?= htmlspecialchars($cliente['correo']) ?></td>
                                <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="index.php?controller=cliente&action=editar&id=<?= htmlspecialchars((string) $cliente['id_cliente']) ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <form method="POST" action="index.php?controller=cliente&action=eliminar" onsubmit="return confirm('¿Desea eliminar este cliente?');">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $cliente['id_cliente']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay clientes registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
