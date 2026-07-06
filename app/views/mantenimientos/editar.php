<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Editar mantenimiento</h1>
                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=mantenimiento&action=actualizar" class="needs-validation mantenimiento-edit-form" novalidate>
                    <input type="hidden" name="id_mantenimiento" value="<?= htmlspecialchars((string) $mantenimiento['id_mantenimiento']) ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cliente</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($mantenimiento['nombres'] . ' ' . $mantenimiento['apellidos'] . ' - ' . $mantenimiento['cedula']) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Moto</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($mantenimiento['placa'] . ' - ' . $mantenimiento['marca'] . ' ' . $mantenimiento['modelo']) ?>" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= htmlspecialchars($mantenimiento['fecha']) ?>" required>
                            <div class="invalid-feedback">Ingrese la fecha.</div>
                            <?php if (!empty($errors['fecha'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['fecha']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo_servicio" class="form-label">Tipo de servicio</label>
                            <input type="text" class="form-control" id="tipo_servicio" name="tipo_servicio" value="<?= htmlspecialchars($mantenimiento['tipo_servicio']) ?>" required>
                            <div class="invalid-feedback">Ingrese el tipo de servicio.</div>
                            <?php if (!empty($errors['tipo_servicio'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['tipo_servicio']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <?php foreach (['Pendiente', 'En proceso', 'Finalizado'] as $estado): ?>
                                    <option value="<?= htmlspecialchars($estado) ?>" <?= $mantenimiento['estado'] === $estado ? 'selected' : '' ?>><?= htmlspecialchars($estado) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Seleccione un estado.</div>
                            <?php if (!empty($errors['estado'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['estado']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label for="costo_mano_obra" class="form-label">Costo mano de obra</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="costo_mano_obra" name="costo_mano_obra" value="<?= htmlspecialchars((string) $mantenimiento['costo_mano_obra']) ?>" required>
                            <div class="invalid-feedback">Ingrese un costo valido.</div>
                            <?php if (!empty($errors['costo_mano_obra'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['costo_mano_obra']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($mantenimiento['descripcion']) ?></textarea>
                            <div class="invalid-feedback">Ingrese la descripcion.</div>
                            <?php if (!empty($errors['descripcion'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['descripcion']) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="index.php?controller=mantenimiento&action=index" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
