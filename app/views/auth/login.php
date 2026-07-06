<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h3 text-center mb-4">Iniciar sesion</h1>
                <p class="text-muted text-center">Sistema web para gestion de mantenimiento de motos.</p>

                <form method="POST" action="index.php?controller=auth&action=login" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($correo ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contrasena</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>

                <div class="mt-4 small text-muted">
                    <strong>Usuario de prueba:</strong> admin@gmail.com / 123456
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
