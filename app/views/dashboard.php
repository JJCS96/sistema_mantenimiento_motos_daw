<?php require __DIR__ . '/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Panel inicial del sistema de mantenimiento de motos.</p>
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

<?php require __DIR__ . '/layout/footer.php'; ?>
