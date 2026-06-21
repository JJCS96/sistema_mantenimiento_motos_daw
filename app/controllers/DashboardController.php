<?php

require_once __DIR__ . '/BaseController.php';

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $cards = [
            ['titulo' => 'Clientes', 'total' => 0, 'descripcion' => 'Modulo listo para mostrar el total de clientes.'],
            ['titulo' => 'Motos', 'total' => 0, 'descripcion' => 'Modulo listo para mostrar el total de motos.'],
            ['titulo' => 'Repuestos', 'total' => 0, 'descripcion' => 'Modulo listo para mostrar el total de repuestos.'],
            ['titulo' => 'Mantenimientos', 'total' => 0, 'descripcion' => 'Modulo listo para mostrar el total de mantenimientos.'],
        ];

        $this->render('dashboard', [
            'title' => 'Dashboard',
            'cards' => $cards,
        ]);
    }
}
