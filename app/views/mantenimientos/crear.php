<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Nuevo mantenimiento</h1>
                <p class="text-muted">Registre los datos generales del mantenimiento y hasta 3 repuestos usados.</p>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
                <?php endif; ?>

                <?php if (!empty($errors['detalles'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['detalles']) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=mantenimiento&action=guardar" class="needs-validation mantenimiento-form" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_cliente" class="form-label">Cliente</label>
                            <select class="form-select" id="id_cliente" name="id_cliente" required>
                                <option value="">Seleccione un cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= htmlspecialchars((string) $cliente['id_cliente']) ?>" <?= $old['id_cliente'] === (string) $cliente['id_cliente'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apellidos'] . ' - ' . $cliente['cedula']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un cliente.</div>
                            <?php if (!empty($errors['id_cliente'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['id_cliente']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="id_moto" class="form-label">Moto</label>
                            <select class="form-select" id="id_moto" name="id_moto" required>
                                <option value="">Seleccione una moto</option>
                                <?php foreach ($motos as $moto): ?>
                                    <option value="<?= htmlspecialchars((string) $moto['id_moto']) ?>" data-cliente="<?= htmlspecialchars((string) $moto['id_cliente']) ?>" <?= $old['id_moto'] === (string) $moto['id_moto'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($moto['placa'] . ' - ' . $moto['marca'] . ' ' . $moto['modelo'] . ' / ' . $moto['nombres'] . ' ' . $moto['apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione una moto.</div>
                            <?php if (!empty($errors['id_moto'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['id_moto']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= htmlspecialchars($old['fecha']) ?>" required>
                            <div class="invalid-feedback">Ingrese la fecha.</div>
                            <?php if (!empty($errors['fecha'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['fecha']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo_servicio" class="form-label">Tipo de servicio</label>
                            <input type="text" class="form-control" id="tipo_servicio" name="tipo_servicio" value="<?= htmlspecialchars($old['tipo_servicio']) ?>" required>
                            <div class="invalid-feedback">Ingrese el tipo de servicio.</div>
                            <?php if (!empty($errors['tipo_servicio'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['tipo_servicio']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <?php foreach (['Pendiente', 'En proceso', 'Finalizado'] as $estado): ?>
                                    <option value="<?= htmlspecialchars($estado) ?>" <?= $old['estado'] === $estado ? 'selected' : '' ?>><?= htmlspecialchars($estado) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un estado.</div>
                            <?php if (!empty($errors['estado'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['estado']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="costo_mano_obra" class="form-label">Costo mano de obra</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="costo_mano_obra" name="costo_mano_obra" value="<?= htmlspecialchars($old['costo_mano_obra']) ?>" required>
                            <div class="invalid-feedback">Ingrese un costo valido.</div>
                            <?php if (!empty($errors['costo_mano_obra'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['costo_mano_obra']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($old['descripcion']) ?></textarea>
                            <div class="invalid-feedback">Ingrese la descripcion.</div>
                            <?php if (!empty($errors['descripcion'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['descripcion']) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h2 class="h5 mb-3">Repuestos usados</h2>
                    <p class="text-muted small">Puede registrar hasta 3 repuestos. Los campos vacios se ignoran.</p>

                    <div class="row g-3">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <div class="col-lg-8">
                                <label class="form-label">Repuesto <?= $i + 1 ?></label>
                                <select class="form-select repuesto-select" name="repuesto_ids[]">
                                    <option value="">Seleccione un repuesto</option>
                                    <?php foreach ($repuestos as $repuesto): ?>
                                        <option value="<?= htmlspecialchars((string) $repuesto['id_repuesto']) ?>" data-stock="<?= htmlspecialchars((string) $repuesto['stock']) ?>" <?= ($old['repuesto_ids'][$i] ?? '') === (string) $repuesto['id_repuesto'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($repuesto['nombre'] . ' - Stock: ' . $repuesto['stock'] . ' - $' . number_format((float) $repuesto['precio'], 2)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Cantidad <?= $i + 1 ?></label>
                                <input type="number" min="1" class="form-control repuesto-cantidad" name="cantidades[]" value="<?= htmlspecialchars($old['cantidades'][$i] ?? '') ?>">
                                <div class="invalid-feedback">Ingrese una cantidad valida.</div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="index.php?controller=mantenimiento&action=index" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
