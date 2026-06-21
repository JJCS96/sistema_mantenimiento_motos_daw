<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Editar cliente</h1>
                <p class="text-muted">Actualice los datos del cliente seleccionado.</p>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=cliente&action=actualizar" class="needs-validation cliente-form" novalidate>
                    <input type="hidden" name="id_cliente" value="<?= htmlspecialchars((string) $cliente['id_cliente']) ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" maxlength="10" value="<?= htmlspecialchars($cliente['cedula']) ?>" required>
                            <div class="invalid-feedback">Ingrese una cedula de 10 digitos.</div>
                            <?php if (!empty($errors['cedula'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['cedula']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" maxlength="10" value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
                            <div class="invalid-feedback">Ingrese un telefono de 10 digitos.</div>
                            <?php if (!empty($errors['telefono'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['telefono']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" value="<?= htmlspecialchars($cliente['nombres']) ?>" required>
                            <div class="invalid-feedback">Ingrese los nombres.</div>
                            <?php if (!empty($errors['nombres'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['nombres']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= htmlspecialchars($cliente['apellidos']) ?>" required>
                            <div class="invalid-feedback">Ingrese los apellidos.</div>
                            <?php if (!empty($errors['apellidos'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['apellidos']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>" required>
                            <div class="invalid-feedback">Ingrese un correo valido.</div>
                            <?php if (!empty($errors['correo'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['correo']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label for="direccion" class="form-label">Direccion</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3" required><?= htmlspecialchars($cliente['direccion']) ?></textarea>
                            <div class="invalid-feedback">Ingrese la direccion.</div>
                            <?php if (!empty($errors['direccion'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['direccion']) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="index.php?controller=cliente&action=index" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
