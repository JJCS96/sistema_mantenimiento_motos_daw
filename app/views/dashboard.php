<?php require __DIR__ . '/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Resumen general del taller y actividad reciente del sistema.</p>
    </div>
    <div class="text-end">
        <span class="text-muted small d-block">Total generado por mantenimientos</span>
        <span class="h4 text-primary">$<?= htmlspecialchars(number_format((float) $totalGenerado, 2)) ?></span>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($cards as $card): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5"><?= htmlspecialchars($card['titulo']) ?></h2>
                    <p class="display-6 fw-bold mb-2"><?= htmlspecialchars((string) $card['total']) ?></p>
                    <p class="text-muted mb-0"><?= htmlspecialchars($card['descripcion']) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Ultimos 5 mantenimientos</h2>
                    <a href="index.php?controller=mantenimiento&action=index" class="btn btn-outline-primary btn-sm">Ver todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Moto</th>
                                <th>Servicio</th>
                                <th>Estado</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ultimosMantenimientos)): ?>
                                <?php foreach ($ultimosMantenimientos as $mantenimiento): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($mantenimiento['fecha']) ?></td>
                                        <td><?= htmlspecialchars($mantenimiento['nombres'] . ' ' . $mantenimiento['apellidos']) ?></td>
                                        <td><?= htmlspecialchars($mantenimiento['placa'] . ' - ' . $mantenimiento['marca'] . ' ' . $mantenimiento['modelo']) ?></td>
                                        <td><?= htmlspecialchars($mantenimiento['tipo_servicio']) ?></td>
                                        <td><?= htmlspecialchars($mantenimiento['estado']) ?></td>
                                        <td>$<?= htmlspecialchars(number_format((float) $mantenimiento['total'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Todavia no hay mantenimientos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
