<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Nueva moto</h1>
                <p class="text-muted">Registre una moto y asociela a un cliente existente.</p>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=moto&action=guardar" class="needs-validation moto-form" novalidate>
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
                            <label for="placa" class="form-label">Placa</label>
                            <input type="text" class="form-control" id="placa" name="placa" maxlength="10" value="<?= htmlspecialchars($old['placa']) ?>" required>
                            <div class="invalid-feedback">Ingrese la placa.</div>
                            <?php if (!empty($errors['placa'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['placa']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" name="marca" value="<?= htmlspecialchars($old['marca']) ?>" required>
                            <div class="invalid-feedback">Ingrese la marca.</div>
                            <?php if (!empty($errors['marca'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['marca']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" value="<?= htmlspecialchars($old['modelo']) ?>" required>
                            <div class="invalid-feedback">Ingrese el modelo.</div>
                            <?php if (!empty($errors['modelo'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['modelo']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="anio" class="form-label">Anio</label>
                            <input type="number" class="form-control" id="anio" name="anio" min="1990" max="<?= htmlspecialchars((string) $currentYear) ?>" value="<?= htmlspecialchars($old['anio']) ?>" required>
                            <div class="invalid-feedback">Ingrese un anio valido.</div>
                            <?php if (!empty($errors['anio'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['anio']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="color" name="color" value="<?= htmlspecialchars($old['color']) ?>" required>
                            <div class="invalid-feedback">Ingrese el color.</div>
                            <?php if (!empty($errors['color'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['color']) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="index.php?controller=moto&action=index" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
