<?php

require_once __DIR__ . '/BaseController.php';

class MantenimientoController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $this->render('mantenimientos/index', [
            'title' => 'Mantenimientos',
        ]);
    }
}
