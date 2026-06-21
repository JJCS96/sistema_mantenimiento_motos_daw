<?php

require_once __DIR__ . '/BaseController.php';

class MotoController extends BaseController
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $this->render('motos/index', [
            'title' => 'Motos',
        ]);
    }
}
