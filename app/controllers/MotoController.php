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
            $this->setFlash('warning', 'Validacion', 'Cliente no valido.');
        }

        if ($this->motoModel->existePlaca($datos['placa'])) {
            $errors['placa'] = 'La placa ya esta registrada.';
            $this->setFlash('warning', 'Validacion', 'La placa ya esta registrada.');
        }

        if (!empty($errors)) {
            $this->setFlash('warning', 'Error de validacion', (string) reset($errors));
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
            $this->setFlash('success', 'Correcto', 'Moto registrada correctamente.');
            $this->redirect('moto', 'index');
        }

        $this->setFlash('error', 'Error', 'Error al guardar la moto.');
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
            $this->setFlash('error', 'No encontrada', 'La moto solicitada no existe.');
            $this->redirect('moto', 'index');
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
            $this->setFlash('error', 'No encontrada', 'No se puede editar una moto inexistente.');
            $this->redirect('moto', 'index');
        }

        $datos = $this->sanitizeFormData($_POST);
        $errors = $this->validateMotoData($datos);

        if (!$this->clienteModel->obtenerPorId((int) $datos['id_cliente'])) {
            $errors['id_cliente'] = 'Cliente no valido.';
            $this->setFlash('warning', 'Validacion', 'Cliente no valido.');
        }

        if ($this->motoModel->existePlaca($datos['placa'], $id)) {
            $errors['placa'] = 'La placa ya esta registrada.';
            $this->setFlash('warning', 'Validacion', 'La placa ya esta registrada.');
        }

        if (!empty($errors)) {
            $this->setFlash('warning', 'Error de validacion', (string) reset($errors));
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
            $this->setFlash('success', 'Correcto', 'Moto actualizada correctamente.');
            $this->redirect('moto', 'index');
        }

        $moto = array_merge(['id_moto' => $id], $datos);
        $this->setFlash('error', 'Error', 'Error al actualizar la moto.');
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
        if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'GET'], true)) {
            $this->redirect('moto', 'index');
        }

        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $moto = $this->motoModel->obtenerPorId($id);

        if (!$moto) {
            $this->setFlash('error', 'No encontrada', 'No se puede eliminar una moto inexistente.');
            $this->redirect('moto', 'index');
        }

        if ($this->motoModel->eliminar($id)) {
            $this->setFlash('success', 'Correcto', 'Moto eliminada correctamente.');
            $this->redirect('moto', 'index');
        }

        $this->setFlash('error', 'Error', 'Error al eliminar la moto.');
        $this->redirect('moto', 'index');
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
