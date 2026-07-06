<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Repuesto.php';

class RepuestoController extends BaseController
{
    private Repuesto $repuestoModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->repuestoModel = new Repuesto();
    }

    public function index(): void
    {
        $repuestos = $this->repuestoModel->obtenerTodos();

        $this->render('repuestos/index', [
            'title' => 'Repuestos',
            'repuestos' => $repuestos,
        ]);
    }

    public function crear(): void
    {
        $this->render('repuestos/crear', [
            'title' => 'Nuevo repuesto',
            'errors' => [],
            'old' => $this->emptyFormData(),
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('repuesto', 'index');
        }

        $datos = $this->sanitizeFormData($_POST);
        $errors = $this->validateRepuestoData($datos);

        if ($this->repuestoModel->existeNombre($datos['nombre'])) {
            $errors['nombre'] = 'El repuesto ya existe.';
            $this->setFlash('warning', 'Validacion', 'El repuesto ya existe.');
        }

        if (!empty($errors)) {
            $this->setFlash('warning', 'Error de validacion', (string) reset($errors));
            $this->render('repuestos/crear', [
                'title' => 'Nuevo repuesto',
                'errors' => $errors,
                'old' => $datos,
            ]);
            return;
        }

        if ($this->repuestoModel->crear($datos)) {
            $this->setFlash('success', 'Correcto', 'Repuesto registrado correctamente.');
            $this->redirect('repuesto', 'index');
        }

        $this->setFlash('error', 'Error', 'Error al guardar el repuesto.');
        $this->render('repuestos/crear', [
            'title' => 'Nuevo repuesto',
            'errors' => ['general' => 'Error al guardar el repuesto.'],
            'old' => $datos,
        ]);
    }

    public function editar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $repuesto = $this->repuestoModel->obtenerPorId($id);

        if (!$repuesto) {
            $this->setFlash('error', 'No encontrado', 'El repuesto solicitado no existe.');
            $this->redirect('repuesto', 'index');
        }

        $this->render('repuestos/editar', [
            'title' => 'Editar repuesto',
            'errors' => [],
            'repuesto' => $repuesto,
        ]);
    }

    public function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('repuesto', 'index');
        }

        $id = (int) ($_POST['id_repuesto'] ?? 0);
        $repuesto = $this->repuestoModel->obtenerPorId($id);

        if (!$repuesto) {
            $this->setFlash('error', 'No encontrado', 'No se puede editar un repuesto inexistente.');
            $this->redirect('repuesto', 'index');
        }

        $datos = $this->sanitizeFormData($_POST);
        $errors = $this->validateRepuestoData($datos);

        if ($this->repuestoModel->existeNombre($datos['nombre'], $id)) {
            $errors['nombre'] = 'El repuesto ya existe.';
            $this->setFlash('warning', 'Validacion', 'El repuesto ya existe.');
        }

        if (!empty($errors)) {
            $this->setFlash('warning', 'Error de validacion', (string) reset($errors));
            $repuesto = array_merge(['id_repuesto' => $id], $datos);
            $this->render('repuestos/editar', [
                'title' => 'Editar repuesto',
                'errors' => $errors,
                'repuesto' => $repuesto,
            ]);
            return;
        }

        if ($this->repuestoModel->actualizar($id, $datos)) {
            $this->setFlash('success', 'Correcto', 'Repuesto actualizado correctamente.');
            $this->redirect('repuesto', 'index');
        }

        $repuesto = array_merge(['id_repuesto' => $id], $datos);
        $this->setFlash('error', 'Error', 'Error al actualizar el repuesto.');
        $this->render('repuestos/editar', [
            'title' => 'Editar repuesto',
            'errors' => ['general' => 'Error al actualizar el repuesto.'],
            'repuesto' => $repuesto,
        ]);
    }

    public function eliminar(): void
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'GET'], true)) {
            $this->redirect('repuesto', 'index');
        }

        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $repuesto = $this->repuestoModel->obtenerPorId($id);

        if (!$repuesto) {
            $this->setFlash('error', 'No encontrado', 'No se puede eliminar un repuesto inexistente.');
            $this->redirect('repuesto', 'index');
        }

        if ($this->repuestoModel->eliminar($id)) {
            $this->setFlash('success', 'Correcto', 'Repuesto eliminado correctamente.');
            $this->redirect('repuesto', 'index');
        }

        $this->setFlash('error', 'Error', 'Error al eliminar el repuesto.');
        $this->redirect('repuesto', 'index');
    }

    private function sanitizeFormData(array $input): array
    {
        return [
            'nombre' => trim($input['nombre'] ?? ''),
            'descripcion' => trim($input['descripcion'] ?? ''),
            'stock' => trim($input['stock'] ?? ''),
            'precio' => trim($input['precio'] ?? ''),
        ];
    }

    private function validateRepuestoData(array $datos): array
    {
        $errors = [];

        foreach ($datos as $campo => $valor) {
            if ($valor === '') {
                $errors[$campo] = 'Todos los campos son obligatorios.';
            }
        }

        if ($datos['stock'] !== '' && filter_var($datos['stock'], FILTER_VALIDATE_INT) === false) {
            $errors['stock'] = 'Stock invalido.';
        }

        if ($datos['stock'] !== '' && filter_var($datos['stock'], FILTER_VALIDATE_INT) !== false && (int) $datos['stock'] < 0) {
            $errors['stock'] = 'El stock no puede ser negativo.';
        }

        if ($datos['precio'] !== '' && !is_numeric($datos['precio'])) {
            $errors['precio'] = 'Precio invalido.';
        }

        if ($datos['precio'] !== '' && is_numeric($datos['precio']) && (float) $datos['precio'] <= 0) {
            $errors['precio'] = 'El precio debe ser mayor a 0.';
        }

        return $errors;
    }

    private function emptyFormData(): array
    {
        return [
            'nombre' => '',
            'descripcion' => '',
            'stock' => '',
            'precio' => '',
        ];
    }
}
