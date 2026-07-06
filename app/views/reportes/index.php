<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Reportes</h1>
        <p class="text-muted mb-0">Consultas basicas para apoyar la gestion del taller.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Mantenimientos por rango de fechas</h2>
                <form method="POST" action="index.php?controller=reporte&action=index" class="needs-validation reporte-fechas-form" novalidate>
                    <input type="hidden" name="reporte_accion" value="por_fechas">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" value="<?= htmlspecialchars($filtros['fecha_inicio']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha fin</label>
                            <input type="date" class="form-control" name="fecha_fin" value="<?= htmlspecialchars($filtros['fecha_fin']) ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Buscar</button>
                </form>

                <?php if (!empty($resultados['fechas'])): ?>
                    <div class="table-responsive mt-4">
                        <table class="table table-sm table-hover align-middle mb-0">
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
                                <?php foreach ($resultados['fechas'] as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['fecha']) ?></td>
                                        <td><?= htmlspecialchars($item['nombres'] . ' ' . $item['apellidos']) ?></td>
                                        <td><?= htmlspecialchars($item['placa'] . ' - ' . $item['marca'] . ' ' . $item['modelo']) ?></td>
                                        <td><?= htmlspecialchars($item['tipo_servicio']) ?></td>
                                        <td><?= htmlspecialchars($item['estado']) ?></td>
                                        <td>$<?= htmlspecialchars(number_format((float) $item['total'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Mantenimientos por cliente</h2>
                <form method="POST" action="index.php?controller=reporte&action=index" class="needs-validation reporte-cliente-form" novalidate>
                    <input type="hidden" name="reporte_accion" value="por_cliente">
                    <label class="form-label">Cliente</label>
                    <select class="form-select" name="id_cliente" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= htmlspecialchars((string) $cliente['id_cliente']) ?>" <?= $filtros['id_cliente'] === (string) $cliente['id_cliente'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apellidos'] . ' - ' . $cliente['cedula']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary mt-3">Buscar</button>
                </form>

                <?php if (!empty($resultados['cliente'])): ?>
                    <div class="table-responsive mt-4">
                        <table class="table table-sm table-hover align-middle mb-0">
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
                                <?php foreach ($resultados['cliente'] as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['fecha']) ?></td>
                                        <td><?= htmlspecialchars($item['nombres'] . ' ' . $item['apellidos']) ?></td>
                                        <td><?= htmlspecialchars($item['placa'] . ' - ' . $item['marca'] . ' ' . $item['modelo']) ?></td>
                                        <td><?= htmlspecialchars($item['tipo_servicio']) ?></td>
                                        <td><?= htmlspecialchars($item['estado']) ?></td>
                                        <td>$<?= htmlspecialchars(number_format((float) $item['total'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Mantenimientos por estado</h2>
                <form method="POST" action="index.php?controller=reporte&action=index" class="needs-validation reporte-estado-form" novalidate>
                    <input type="hidden" name="reporte_accion" value="por_estado">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="estado" required>
                        <option value="">Seleccione un estado</option>
                        <?php foreach (['Pendiente', 'En proceso', 'Finalizado'] as $estado): ?>
                            <option value="<?= htmlspecialchars($estado) ?>" <?= $filtros['estado'] === $estado ? 'selected' : '' ?>><?= htmlspecialchars($estado) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary mt-3">Buscar</button>
                </form>

                <?php if (!empty($resultados['estado'])): ?>
                    <div class="table-responsive mt-4">
                        <table class="table table-sm table-hover align-middle mb-0">
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
                                <?php foreach ($resultados['estado'] as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['fecha']) ?></td>
                                        <td><?= htmlspecialchars($item['nombres'] . ' ' . $item['apellidos']) ?></td>
                                        <td><?= htmlspecialchars($item['placa'] . ' - ' . $item['marca'] . ' ' . $item['modelo']) ?></td>
                                        <td><?= htmlspecialchars($item['tipo_servicio']) ?></td>
                                        <td><?= htmlspecialchars($item['estado']) ?></td>
                                        <td>$<?= htmlspecialchars(number_format((float) $item['total'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Repuestos con bajo stock</h2>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Stock</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($resultados['bajo_stock'])): ?>
                                <?php foreach ($resultados['bajo_stock'] as $repuesto): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($repuesto['nombre']) ?></td>
                                        <td><?= htmlspecialchars($repuesto['descripcion']) ?></td>
                                        <td>
                                            <?= htmlspecialchars((string) $repuesto['stock']) ?>
                                            <span class="badge text-bg-warning ms-2">Bajo stock</span>
                                        </td>
                                        <td>$<?= htmlspecialchars(number_format((float) $repuesto['precio'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No hay repuestos con bajo stock.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
