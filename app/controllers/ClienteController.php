<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController extends BaseController
{
    private Cliente $clienteModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->clienteModel = new Cliente();
    }

    public function index(): void
    {
        $clientes = $this->clienteModel->obtenerTodos();

        $this->render('clientes/index', [
            'title' => 'Clientes',
            'clientes' => $clientes,
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? '',
        ]);
    }

    public function crear(): void
    {
        $this->render('clientes/crear', [
            'title' => 'Nuevo cliente',
            'errors' => [],
            'old' => $this->emptyFormData(),
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cliente', 'index');
        }

        $datos = $this->sanitizeFormData($_POST);
        $errors = $this->validateClienteData($datos);

        if ($this->clienteModel->existeCedula($datos['cedula'])) {
            $errors['cedula'] = 'La cedula ya esta registrada.';
        }

        if ($this->clienteModel->existeCorreo($datos['correo'])) {
            $errors['correo'] = 'El correo ya esta registrado.';
        }

        if (!empty($errors)) {
            $this->render('clientes/crear', [
                'title' => 'Nuevo cliente',
                'errors' => $errors,
                'old' => $datos,
            ]);
            return;
        }

        if ($this->clienteModel->crear($datos)) {
            $this->redirect('cliente', 'index', ['success' => 'Cliente registrado correctamente.']);
        }

        $this->render('clientes/crear', [
            'title' => 'Nuevo cliente',
            'errors' => ['general' => 'Error al guardar el cliente.'],
            'old' => $datos,
        ]);
    }

    public function editar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $cliente = $this->clienteModel->obtenerPorId($id);

        if (!$cliente) {
            $this->redirect('cliente', 'index', ['error' => 'El cliente solicitado no existe.']);
        }

        $this->render('clientes/editar', [
            'title' => 'Editar cliente',
            'errors' => [],
            'cliente' => $cliente,
        ]);
    }

    public function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cliente', 'index');
        }

        $id = (int) ($_POST['id_cliente'] ?? 0);
        $cliente = $this->clienteModel->obtenerPorId($id);

        if (!$cliente) {
            $this->redirect('cliente', 'index', ['error' => 'No se puede editar un cliente inexistente.']);
        }

        $datos = $this->sanitizeFormData($_POST);
        $errors = $this->validateClienteData($datos);

        if ($this->clienteModel->existeCedula($datos['cedula'], $id)) {
            $errors['cedula'] = 'La cedula ya esta registrada.';
        }

        if ($this->clienteModel->existeCorreo($datos['correo'], $id)) {
            $errors['correo'] = 'El correo ya esta registrado.';
        }

        if (!empty($errors)) {
            $cliente = array_merge(['id_cliente' => $id], $datos);
            $this->render('clientes/editar', [
                'title' => 'Editar cliente',
                'errors' => $errors,
                'cliente' => $cliente,
            ]);
            return;
        }

        if ($this->clienteModel->actualizar($id, $datos)) {
            $this->redirect('cliente', 'index', ['success' => 'Cliente actualizado correctamente.']);
        }

        $cliente = array_merge(['id_cliente' => $id], $datos);
        $this->render('clientes/editar', [
            'title' => 'Editar cliente',
            'errors' => ['general' => 'Error al actualizar el cliente.'],
            'cliente' => $cliente,
        ]);
    }

    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cliente', 'index');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $cliente = $this->clienteModel->obtenerPorId($id);

        if (!$cliente) {
            $this->redirect('cliente', 'index', ['error' => 'No se puede eliminar un cliente inexistente.']);
        }

        if ($this->clienteModel->eliminar($id)) {
            $this->redirect('cliente', 'index', ['success' => 'Cliente eliminado correctamente.']);
        }

        $this->redirect('cliente', 'index', ['error' => 'Error al eliminar el cliente.']);
    }

    private function sanitizeFormData(array $input): array
    {
        return [
            'cedula' => trim($input['cedula'] ?? ''),
            'nombres' => trim($input['nombres'] ?? ''),
            'apellidos' => trim($input['apellidos'] ?? ''),
            'telefono' => trim($input['telefono'] ?? ''),
            'correo' => trim($input['correo'] ?? ''),
            'direccion' => trim($input['direccion'] ?? ''),
        ];
    }

    private function validateClienteData(array $datos): array
    {
        $errors = [];

        foreach ($datos as $campo => $valor) {
            if ($valor === '') {
                $errors[$campo] = 'Todos los campos son obligatorios.';
            }
        }

        if ($datos['cedula'] !== '' && !preg_match('/^\d{10}$/', $datos['cedula'])) {
            $errors['cedula'] = 'La cedula debe tener 10 digitos.';
        }

        if ($datos['telefono'] !== '' && !preg_match('/^\d{10}$/', $datos['telefono'])) {
            $errors['telefono'] = 'El telefono debe tener 10 digitos.';
        }

        if ($datos['correo'] !== '' && !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors['correo'] = 'Ingrese un correo valido.';
        }

        return $errors;
    }

    private function emptyFormData(): array
    {
        return [
            'cedula' => '',
            'nombres' => '',
            'apellidos' => '',
            'telefono' => '',
            'correo' => '',
            'direccion' => '',
        ];
    }
}
