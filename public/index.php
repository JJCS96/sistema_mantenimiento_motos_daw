<?php
session_start();

$controllerName = $_GET['controller'] ?? (isset($_SESSION['usuario']) ? 'dashboard' : 'auth');
$actionName = $_GET['action'] ?? (isset($_SESSION['usuario']) ? 'index' : 'login');

$controllerMap = [
    'auth' => 'AuthController',
    'dashboard' => 'DashboardController',
    'cliente' => 'ClienteController',
    'moto' => 'MotoController',
    'repuesto' => 'RepuestoController',
    'mantenimiento' => 'MantenimientoController',
    'reporte' => 'ReporteController',
];

if (!isset($controllerMap[$controllerName])) {
    http_response_code(404);
    echo '404 - Controlador no encontrado';
    exit;
}

$controllerClass = $controllerMap[$controllerName];
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(500);
    echo 'Error interno: archivo del controlador no encontrado';
    exit;
}

require_once $controllerFile;

$controller = new $controllerClass();

if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    echo '404 - Accion no encontrada';
    exit;
}

$controller->$actionName();
