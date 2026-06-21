<?php
$title = $title ?? 'Sistema de Mantenimiento de Motos';
$currentController = $_GET['controller'] ?? '';
$usuarioSesion = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php?controller=dashboard&action=index">Taller de Motos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrincipal" aria-controls="navbarPrincipal" aria-expanded="false" aria-label="Mostrar menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPrincipal">
                <?php if ($usuarioSesion): ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link <?= $currentController === 'dashboard' ? 'active' : '' ?>" href="index.php?controller=dashboard&action=index">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentController === 'cliente' ? 'active' : '' ?>" href="index.php?controller=cliente&action=index">Clientes</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentController === 'moto' ? 'active' : '' ?>" href="index.php?controller=moto&action=index">Motos</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentController === 'repuesto' ? 'active' : '' ?>" href="index.php?controller=repuesto&action=index">Repuestos</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentController === 'mantenimiento' ? 'active' : '' ?>" href="index.php?controller=mantenimiento&action=index">Mantenimientos</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentController === 'reporte' ? 'active' : '' ?>" href="index.php?controller=reporte&action=index">Reportes</a></li>
                    </ul>
                    <div class="d-flex align-items-center gap-3 text-white">
                        <span class="small"><?= htmlspecialchars($usuarioSesion['nombre']) ?></span>
                        <a class="btn btn-outline-light btn-sm" href="index.php?controller=auth&action=logout">Cerrar sesion</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container py-4">
