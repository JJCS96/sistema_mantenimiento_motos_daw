<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Nuevo repuesto</h1>
                <p class="text-muted">Complete el formulario para registrar un repuesto.</p>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=repuesto&action=guardar" class="needs-validation repuesto-form" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($old['nombre']) ?>" required>
                            <div class="invalid-feedback">Ingrese el nombre.</div>
                            <?php if (!empty($errors['nombre'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['nombre']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" value="<?= htmlspecialchars($old['stock']) ?>" required>
                            <div class="invalid-feedback">Ingrese un stock valido.</div>
                            <?php if (!empty($errors['stock'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['stock']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="precio" name="precio" value="<?= htmlspecialchars($old['precio']) ?>" required>
                            <div class="invalid-feedback">Ingrese un precio valido.</div>
                            <?php if (!empty($errors['precio'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['precio']) ?></div><?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($old['descripcion']) ?></textarea>
                            <div class="invalid-feedback">Ingrese la descripcion.</div>
                            <?php if (!empty($errors['descripcion'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errors['descripcion']) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="index.php?controller=repuesto&action=index" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
