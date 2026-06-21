<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../models/Cliente.php';

class MotoController extends BaseController
{
    private Moto $motoModel;
    private Cliente $clienteModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->motoModel = new Moto();
        $this->clienteModel = new Cliente();
    }

    public function index(): void
    {
        $motos = $this->motoModel->obtenerTodos();

        $this->render('motos/index', [
            'title' => 'Motos',
            'motos' => $motos,
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? '',
        ]);
    }

    public function crear(): void
    {
        $clientes = $this->clienteModel->obtenerTodos();

        $this->render('motos/crear', [
            'title' => 'Nueva moto',
            'errors' => [],
            'old' => $this->emptyFormData(),
            'clientes' => $clientes,
            'currentYear' => (int) date('Y'),
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('moto', 'index');
        }

        $datos = $this->sanitizeFormData($_POST);
        $errors = $this->validateMotoData($datos);

        if (!$this->clienteModel->obtenerPorId((int) $datos['id_cliente'])) {
            $errors['id_cliente'] = 'Cliente no valido.';
        }

        if ($this->motoModel->existePlaca($datos['placa'])) {
            $errors['placa'] = 'La placa ya esta registrada.';
        }

        if (!empty($errors)) {
            $this->render('motos/crear', [
                'title' => 'Nueva moto',
                'errors' => $errors,
                'old' => $datos,
                'clientes' => $this->clienteModel->obtenerTodos(),
                'currentYear' => (int) date('Y'),
            ]);
            return;
        }

        if ($this->motoModel->crear($datos)) {
            $this->redirect('moto', 'index', ['success' => 'Moto registrada correctamente.']);
        }

        $this->render('motos/crear', [
            'title' => 'Nueva moto',
            'errors' => ['general' => 'Error al guardar la moto.'],
            'old' => $datos,
            'clientes' => $this->clienteModel->obtenerTodos(),
            'currentYear' => (int) date('Y'),
        ]);
    }

    public function editar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $moto = $this->motoModel->obtenerPorId($id);

        if (!$moto) {
            $this->redirect('moto', 'index', ['error' => 'La moto solicitada no existe.']);
        }

        $this->render('motos/editar', [
            'title' => 'Editar moto',
            'errors' => [],
            'moto' => $moto,
            'clientes' => $this->clienteModel->obtenerTodos(),
            'currentYear' => (int) date('Y'),
        ]);
    }

    public function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('moto', 'index');
        }

        $id = (int) ($_POST['id_moto'] ?? 0);
        $moto = $this->motoModel->obtenerPorId($id);

        if (!$moto) {
            $this->redirect('moto', 'index', ['error' => 'No se puede editar una moto inexistente.']);
        }

        $datos = $this->sanitizeFormData($_POST);
        $errors = $this->validateMotoData($datos);

        if (!$this->clienteModel->obtenerPorId((int) $datos['id_cliente'])) {
            $errors['id_cliente'] = 'Cliente no valido.';
        }

        if ($this->motoModel->existePlaca($datos['placa'], $id)) {
            $errors['placa'] = 'La placa ya esta registrada.';
        }

        if (!empty($errors)) {
            $moto = array_merge(['id_moto' => $id], $datos);
            $this->render('motos/editar', [
                'title' => 'Editar moto',
                'errors' => $errors,
                'moto' => $moto,
                'clientes' => $this->clienteModel->obtenerTodos(),
                'currentYear' => (int) date('Y'),
            ]);
            return;
        }

        if ($this->motoModel->actualizar($id, $datos)) {
            $this->redirect('moto', 'index', ['success' => 'Moto actualizada correctamente.']);
        }

        $moto = array_merge(['id_moto' => $id], $datos);
        $this->render('motos/editar', [
            'title' => 'Editar moto',
            'errors' => ['general' => 'Error al actualizar la moto.'],
            'moto' => $moto,
            'clientes' => $this->clienteModel->obtenerTodos(),
            'currentYear' => (int) date('Y'),
        ]);
    }

    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('moto', 'index');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $moto = $this->motoModel->obtenerPorId($id);

        if (!$moto) {
            $this->redirect('moto', 'index', ['error' => 'No se puede eliminar una moto inexistente.']);
        }

        if ($this->motoModel->eliminar($id)) {
            $this->redirect('moto', 'index', ['success' => 'Moto eliminada correctamente.']);
        }

        $this->redirect('moto', 'index', ['error' => 'Error al eliminar la moto.']);
    }

    private function sanitizeFormData(array $input): array
    {
        return [
            'id_cliente' => trim($input['id_cliente'] ?? ''),
            'placa' => strtoupper(trim($input['placa'] ?? '')),
            'marca' => trim($input['marca'] ?? ''),
            'modelo' => trim($input['modelo'] ?? ''),
            'anio' => trim($input['anio'] ?? ''),
            'color' => trim($input['color'] ?? ''),
        ];
    }

    private function validateMotoData(array $datos): array
    {
        $errors = [];
        $currentYear = (int) date('Y');

        foreach ($datos as $campo => $valor) {
            if ($valor === '') {
                $errors[$campo] = 'Todos los campos son obligatorios.';
            }
        }

        if ($datos['anio'] !== '' && !ctype_digit($datos['anio'])) {
            $errors['anio'] = 'El anio debe ser numerico.';
        }

        if ($datos['anio'] !== '' && ctype_digit($datos['anio'])) {
            $anio = (int) $datos['anio'];

            if ($anio < 1990) {
                $errors['anio'] = 'El anio no puede ser menor a 1990.';
            } elseif ($anio > $currentYear) {
                $errors['anio'] = 'El anio no puede ser mayor al actual.';
            }
        }

        return $errors;
    }

    private function emptyFormData(): array
    {
        return [
            'id_cliente' => '',
            'placa' => '',
            'marca' => '',
            'modelo' => '',
            'anio' => '',
            'color' => '',
        ];
    }
}
