<?php

require_once __DIR__ . '/BaseController.php';

class ReporteController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $this->render('reportes/index', [
            'title' => 'Reportes',
        ]);
    }
}
