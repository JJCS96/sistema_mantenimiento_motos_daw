<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../models/Repuesto.php';
require_once __DIR__ . '/../models/Mantenimiento.php';

class DashboardController extends BaseController
{
    private Cliente $clienteModel;
    private Moto $motoModel;
    private Repuesto $repuestoModel;
    private Mantenimiento $mantenimientoModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->clienteModel = new Cliente();
        $this->motoModel = new Moto();
        $this->repuestoModel = new Repuesto();
        $this->mantenimientoModel = new Mantenimiento();
    }

    public function index(): void
    {
        $cards = [
            ['titulo' => 'Clientes', 'total' => $this->clienteModel->contarTodos(), 'descripcion' => 'Total de clientes registrados.'],
            ['titulo' => 'Motos', 'total' => $this->motoModel->contarTodos(), 'descripcion' => 'Total de motos registradas.'],
            ['titulo' => 'Repuestos', 'total' => $this->repuestoModel->contarTodos(), 'descripcion' => 'Total de repuestos disponibles.'],
            ['titulo' => 'Mantenimientos', 'total' => $this->mantenimientoModel->contarTodos(), 'descripcion' => 'Total de mantenimientos registrados.'],
            ['titulo' => 'Pendientes', 'total' => $this->mantenimientoModel->contarPorEstado('Pendiente'), 'descripcion' => 'Mantenimientos pendientes.'],
            ['titulo' => 'En proceso', 'total' => $this->mantenimientoModel->contarPorEstado('En proceso'), 'descripcion' => 'Mantenimientos en proceso.'],
            ['titulo' => 'Finalizados', 'total' => $this->mantenimientoModel->contarPorEstado('Finalizado'), 'descripcion' => 'Mantenimientos finalizados.'],
            ['titulo' => 'Bajo stock', 'total' => $this->repuestoModel->contarBajoStock(), 'descripcion' => 'Repuestos con stock menor o igual a 5.'],
        ];

        $ultimosMantenimientos = $this->mantenimientoModel->obtenerUltimos(5);
        $totalGenerado = $this->mantenimientoModel->calcularTotalGeneral();

        $this->render('dashboard', [
            'title' => 'Dashboard',
            'cards' => $cards,
            'ultimosMantenimientos' => $ultimosMantenimientos,
            'totalGenerado' => $totalGenerado,
        ]);
    }
}
