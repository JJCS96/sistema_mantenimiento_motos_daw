<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Detalle del mantenimiento</h1>
        <p class="text-muted mb-0">Informacion general y repuestos usados.</p>
    </div>
    <a href="index.php?controller=mantenimiento&action=index" class="btn btn-secondary">Volver</a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Datos generales</h2>
                <p><strong>Cliente:</strong> <?= htmlspecialchars($mantenimiento['nombres'] . ' ' . $mantenimiento['apellidos'] . ' - ' . $mantenimiento['cedula']) ?></p>
                <p><strong>Moto:</strong> <?= htmlspecialchars($mantenimiento['placa'] . ' - ' . $mantenimiento['marca'] . ' ' . $mantenimiento['modelo']) ?></p>
                <p><strong>Fecha:</strong> <?= htmlspecialchars($mantenimiento['fecha']) ?></p>
                <p><strong>Tipo de servicio:</strong> <?= htmlspecialchars($mantenimiento['tipo_servicio']) ?></p>
                <p><strong>Descripcion:</strong> <?= htmlspecialchars($mantenimiento['descripcion']) ?></p>
                <p><strong>Costo mano de obra:</strong> $<?= htmlspecialchars(number_format((float) $mantenimiento['costo_mano_obra'], 2)) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($mantenimiento['estado']) ?></p>
                <p class="mb-0"><strong>Total general:</strong> $<?= htmlspecialchars(number_format((float) $mantenimiento['total'], 2)) ?></p>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Repuestos usados</h2>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Repuesto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($detalles)): ?>
                                <?php foreach ($detalles as $detalle): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($detalle['nombre']) ?></td>
                                        <td><?= htmlspecialchars((string) $detalle['cantidad']) ?></td>
                                        <td>$<?= htmlspecialchars(number_format((float) $detalle['precio_unitario'], 2)) ?></td>
                                        <td>$<?= htmlspecialchars(number_format((float) $detalle['subtotal'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay repuestos registrados en este mantenimiento.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <p><strong>Total de repuestos:</strong> $<?= htmlspecialchars(number_format((float) $totalRepuestos, 2)) ?></p>
                    <p class="mb-0"><strong>Total general:</strong> $<?= htmlspecialchars(number_format((float) $mantenimiento['total'], 2)) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
