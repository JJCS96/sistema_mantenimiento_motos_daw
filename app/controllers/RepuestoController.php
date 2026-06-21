<?php

require_once __DIR__ . '/BaseController.php';

class RepuestoController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $this->render('repuestos/index', [
            'title' => 'Repuestos',
        ]);
    }
}
