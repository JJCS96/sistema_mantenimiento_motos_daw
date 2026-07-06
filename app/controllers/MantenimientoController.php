<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Mantenimiento.php';
require_once __DIR__ . '/../models/DetalleMantenimiento.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../models/Repuesto.php';
require_once __DIR__ . '/../../config/database.php';

class MantenimientoController extends BaseController
{
    private Mantenimiento $mantenimientoModel;
    private DetalleMantenimiento $detalleModel;
    private Cliente $clienteModel;
    private Moto $motoModel;
    private Repuesto $repuestoModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->mantenimientoModel = new Mantenimiento();
        $this->detalleModel = new DetalleMantenimiento();
        $this->clienteModel = new Cliente();
        $this->motoModel = new Moto();
        $this->repuestoModel = new Repuesto();
    }

    public function index(): void
    {
        $mantenimientos = $this->mantenimientoModel->obtenerTodos();

        $this->render('mantenimientos/index', [
            'title' => 'Mantenimientos',
            'mantenimientos' => $mantenimientos,
        ]);
    }

    public function crear(): void
    {
        $this->render('mantenimientos/crear', [
            'title' => 'Nuevo mantenimiento',
            'errors' => [],
            'old' => $this->emptyFormData(),
            'clientes' => $this->clienteModel->obtenerTodos(),
            'motos' => $this->motoModel->obtenerTodos(),
            'repuestos' => $this->repuestoModel->obtenerTodos(),
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('mantenimiento', 'index');
        }

        $datos = $this->sanitizeFormData($_POST);
        $detalles = $this->parseDetalles($_POST);
        $errors = $this->validateMantenimientoData($datos, $detalles);

        if (!empty($errors)) {
            $this->setFlash('warning', 'Error de validacion', (string) reset($errors));
            $this->render('mantenimientos/crear', [
                'title' => 'Nuevo mantenimiento',
                'errors' => $errors,
                'old' => $datos,
                'clientes' => $this->clienteModel->obtenerTodos(),
                'motos' => $this->motoModel->obtenerTodos(),
                'repuestos' => $this->repuestoModel->obtenerTodos(),
            ]);
            return;
        }

        $connection = Database::connect();

        try {
            // La transaccion evita guardar el mantenimiento sin su detalle o sin actualizar stock.
            $connection->beginTransaction();

            $idMantenimiento = $this->mantenimientoModel->crear($datos + ['total' => 0]);

            if ($idMantenimiento === false) {
                throw new RuntimeException('No se pudo guardar el mantenimiento.');
            }

            $totalRepuestos = 0;

            foreach ($detalles as $detalle) {
                $repuesto = $this->repuestoModel->obtenerPorId((int) $detalle['id_repuesto']);

                if (!$repuesto) {
                    throw new RuntimeException('Repuesto no valido.');
                }

                if ((int) $detalle['cantidad'] > (int) $repuesto['stock']) {
                    throw new RuntimeException('Stock insuficiente.');
                }

                $precioUnitario = (float) $repuesto['precio'];
                $subtotal = (int) $detalle['cantidad'] * $precioUnitario;

                $saved = $this->detalleModel->agregarDetalle([
                    'id_mantenimiento' => $idMantenimiento,
                    'id_repuesto' => (int) $detalle['id_repuesto'],
                    'cantidad' => (int) $detalle['cantidad'],
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ]);

                if (!$saved) {
                    throw new RuntimeException('No se pudo guardar el detalle del mantenimiento.');
                }

                $newStock = (int) $repuesto['stock'] - (int) $detalle['cantidad'];

                if (!$this->repuestoModel->actualizarStock((int) $repuesto['id_repuesto'], $newStock)) {
                    throw new RuntimeException('No se pudo actualizar el stock del repuesto.');
                }

                $totalRepuestos += $subtotal;
            }

            $totalGeneral = (float) $datos['costo_mano_obra'] + $totalRepuestos;

            if (!$this->mantenimientoModel->actualizarTotal((int) $idMantenimiento, $totalGeneral)) {
                throw new RuntimeException('No se pudo actualizar el total del mantenimiento.');
            }

            $connection->commit();
            $this->setFlash('success', 'Correcto', 'Mantenimiento registrado correctamente.');
            $this->redirect('mantenimiento', 'index');
        } catch (Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            $this->setFlash('error', 'Error', $exception->getMessage());
            $this->render('mantenimientos/crear', [
                'title' => 'Nuevo mantenimiento',
                'errors' => ['general' => $exception->getMessage()],
                'old' => $datos,
                'clientes' => $this->clienteModel->obtenerTodos(),
                'motos' => $this->motoModel->obtenerTodos(),
                'repuestos' => $this->repuestoModel->obtenerTodos(),
            ]);
        }
    }

    public function editar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $mantenimiento = $this->mantenimientoModel->obtenerPorId($id);

        if (!$mantenimiento) {
            $this->setFlash('error', 'No encontrado', 'El mantenimiento solicitado no existe.');
            $this->redirect('mantenimiento', 'index');
        }

        $this->render('mantenimientos/editar', [
            'title' => 'Editar mantenimiento',
            'errors' => [],
            'mantenimiento' => $mantenimiento,
            'clientes' => $this->clienteModel->obtenerTodos(),
            'motos' => $this->motoModel->obtenerTodos(),
        ]);
    }

    public function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('mantenimiento', 'index');
        }

        $id = (int) ($_POST['id_mantenimiento'] ?? 0);
        $mantenimiento = $this->mantenimientoModel->obtenerPorId($id);

        if (!$mantenimiento) {
            $this->setFlash('error', 'No encontrado', 'No se puede editar un mantenimiento inexistente.');
            $this->redirect('mantenimiento', 'index');
        }

        $datos = $this->sanitizeEditFormData($_POST, $mantenimiento);
        $errors = $this->validateEditData($datos);

        if (!$this->clienteModel->obtenerPorId((int) $datos['id_cliente'])) {
            $errors['id_cliente'] = 'Cliente no valido.';
        }

        $moto = $this->motoModel->obtenerPorId((int) $datos['id_moto']);
        if (!$moto) {
            $errors['id_moto'] = 'Moto no valida.';
        } elseif ((int) $moto['id_cliente'] !== (int) $datos['id_cliente']) {
            $errors['id_moto'] = 'La moto no pertenece al cliente seleccionado.';
        }

        if (!empty($errors)) {
            $this->setFlash('warning', 'Error de validacion', (string) reset($errors));
            $mantenimiento = array_merge(['id_mantenimiento' => $id], $mantenimiento, $datos);
            $this->render('mantenimientos/editar', [
                'title' => 'Editar mantenimiento',
                'errors' => $errors,
                'mantenimiento' => $mantenimiento,
                'clientes' => $this->clienteModel->obtenerTodos(),
                'motos' => $this->motoModel->obtenerTodos(),
            ]);
            return;
        }

        try {
            $detalleTotal = $this->detalleModel->calcularTotal($id);
            $totalGeneral = $detalleTotal + (float) $datos['costo_mano_obra'];

            if (!$this->mantenimientoModel->actualizar($id, $datos)) {
                throw new RuntimeException('Error al actualizar el mantenimiento.');
            }

            if (!$this->mantenimientoModel->actualizarTotal($id, $totalGeneral)) {
                throw new RuntimeException('Error al actualizar el total del mantenimiento.');
            }

            $this->setFlash('success', 'Correcto', 'Mantenimiento actualizado correctamente.');
            $this->redirect('mantenimiento', 'index');
        } catch (Throwable $exception) {
            $mantenimiento = array_merge(['id_mantenimiento' => $id], $mantenimiento, $datos);
            $this->setFlash('error', 'Error', $exception->getMessage());
            $this->render('mantenimientos/editar', [
                'title' => 'Editar mantenimiento',
                'errors' => ['general' => $exception->getMessage()],
                'mantenimiento' => $mantenimiento,
                'clientes' => $this->clienteModel->obtenerTodos(),
                'motos' => $this->motoModel->obtenerTodos(),
            ]);
        }
    }

    public function eliminar(): void
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'GET'], true)) {
            $this->redirect('mantenimiento', 'index');
        }

        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $mantenimiento = $this->mantenimientoModel->obtenerPorId($id);

        if (!$mantenimiento) {
            $this->setFlash('error', 'No encontrado', 'No se puede eliminar un mantenimiento inexistente.');
            $this->redirect('mantenimiento', 'index');
        }

        $connection = Database::connect();

        try {
            // Al eliminar, primero se restaura el stock y luego se borra el mantenimiento.
            $connection->beginTransaction();
            $detalles = $this->detalleModel->obtenerPorMantenimiento($id);

            foreach ($detalles as $detalle) {
                $repuesto = $this->repuestoModel->obtenerPorId((int) $detalle['id_repuesto']);

                if (!$repuesto) {
                    throw new RuntimeException('Repuesto no valido.');
                }

                $newStock = (int) $repuesto['stock'] + (int) $detalle['cantidad'];

                if (!$this->repuestoModel->actualizarStock((int) $repuesto['id_repuesto'], $newStock)) {
                    throw new RuntimeException('No se pudo restaurar el stock del repuesto.');
                }
            }

            if (!$this->mantenimientoModel->eliminar($id)) {
                throw new RuntimeException('Error al eliminar el mantenimiento.');
            }

            $connection->commit();
            $this->setFlash('success', 'Correcto', 'Mantenimiento eliminado correctamente.');
            $this->redirect('mantenimiento', 'index');
        } catch (Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            $this->setFlash('error', 'Error', $exception->getMessage());
            $this->redirect('mantenimiento', 'index');
        }
    }

    public function detalle(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $mantenimiento = $this->mantenimientoModel->obtenerPorId($id);

        if (!$mantenimiento) {
            $this->setFlash('error', 'No encontrado', 'El mantenimiento solicitado no existe.');
            $this->redirect('mantenimiento', 'index');
        }

        $detalles = $this->detalleModel->obtenerPorMantenimiento($id);
        $totalRepuestos = $this->detalleModel->calcularTotal($id);

        $this->render('mantenimientos/detalle', [
            'title' => 'Detalle del mantenimiento',
            'mantenimiento' => $mantenimiento,
            'detalles' => $detalles,
            'totalRepuestos' => $totalRepuestos,
        ]);
    }

    private function sanitizeFormData(array $input): array
    {
        return [
            'id_cliente' => trim($input['id_cliente'] ?? ''),
            'id_moto' => trim($input['id_moto'] ?? ''),
            'fecha' => trim($input['fecha'] ?? ''),
            'tipo_servicio' => trim($input['tipo_servicio'] ?? ''),
            'descripcion' => trim($input['descripcion'] ?? ''),
            'costo_mano_obra' => trim($input['costo_mano_obra'] ?? ''),
            'estado' => trim($input['estado'] ?? ''),
            'repuesto_ids' => array_map('trim', $_POST['repuesto_ids'] ?? []),
            'cantidades' => array_map('trim', $_POST['cantidades'] ?? []),
        ];
    }

    private function sanitizeEditFormData(array $input, array $mantenimiento): array
    {
        return [
            'id_cliente' => (string) $mantenimiento['id_cliente'],
            'id_moto' => (string) $mantenimiento['id_moto'],
            'fecha' => trim($input['fecha'] ?? ''),
            'tipo_servicio' => trim($input['tipo_servicio'] ?? ''),
            'descripcion' => trim($input['descripcion'] ?? ''),
            'costo_mano_obra' => trim($input['costo_mano_obra'] ?? ''),
            'estado' => trim($input['estado'] ?? ''),
        ];
    }

    private function parseDetalles(array $input): array
    {
        $repuestoIds = $input['repuesto_ids'] ?? [];
        $cantidades = $input['cantidades'] ?? [];
        $detalles = [];

        foreach ($repuestoIds as $index => $idRepuesto) {
            $idRepuesto = trim((string) $idRepuesto);
            $cantidad = trim((string) ($cantidades[$index] ?? ''));

            if ($idRepuesto === '' && $cantidad === '') {
                continue;
            }

            $detalles[] = [
                'id_repuesto' => $idRepuesto,
                'cantidad' => $cantidad,
            ];
        }

        return $detalles;
    }

    private function validateMantenimientoData(array $datos, array $detalles): array
    {
        $errors = [];
        $estadosValidos = ['Pendiente', 'En proceso', 'Finalizado'];

        if ($datos['id_cliente'] === '' || !$this->clienteModel->obtenerPorId((int) $datos['id_cliente'])) {
            $errors['id_cliente'] = 'Cliente no valido.';
        }

        $moto = null;
        if ($datos['id_moto'] === '') {
            $errors['id_moto'] = 'Moto no valida.';
        } else {
            $moto = $this->motoModel->obtenerPorId((int) $datos['id_moto']);

            if (!$moto) {
                $errors['id_moto'] = 'Moto no valida.';
            } elseif ($datos['id_cliente'] !== '' && (int) $moto['id_cliente'] !== (int) $datos['id_cliente']) {
                $errors['id_moto'] = 'La moto no pertenece al cliente seleccionado.';
            }
        }

        if ($datos['fecha'] === '') {
            $errors['fecha'] = 'La fecha es obligatoria.';
        }

        if ($datos['tipo_servicio'] === '') {
            $errors['tipo_servicio'] = 'El tipo de servicio es obligatorio.';
        }

        if ($datos['descripcion'] === '') {
            $errors['descripcion'] = 'La descripcion es obligatoria.';
        }

        if ($datos['costo_mano_obra'] === '') {
            $errors['costo_mano_obra'] = 'El costo de mano de obra es obligatorio.';
        } elseif (!is_numeric($datos['costo_mano_obra'])) {
            $errors['costo_mano_obra'] = 'El costo de mano de obra debe ser numerico.';
        } elseif ((float) $datos['costo_mano_obra'] < 0) {
            $errors['costo_mano_obra'] = 'El costo de mano de obra no puede ser negativo.';
        }

        if (!in_array($datos['estado'], $estadosValidos, true)) {
            $errors['estado'] = 'El estado no es valido.';
        }

        if (empty($detalles)) {
            $errors['detalles'] = 'Debe seleccionar al menos un repuesto usado.';
        }

        $selectedRepuestos = [];

        foreach ($detalles as $index => $detalle) {
            $rowNumber = $index + 1;

            if ($detalle['id_repuesto'] === '') {
                $errors['detalles'] = 'Complete correctamente los repuestos usados.';
                continue;
            }

            if ($detalle['cantidad'] === '') {
                $errors['detalles'] = 'Complete correctamente las cantidades de repuestos.';
                continue;
            }

            if (in_array($detalle['id_repuesto'], $selectedRepuestos, true)) {
                $errors['detalles'] = 'No repita el mismo repuesto en el mantenimiento.';
                continue;
            }

            $selectedRepuestos[] = $detalle['id_repuesto'];
            $repuesto = $this->repuestoModel->obtenerPorId((int) $detalle['id_repuesto']);

            if (!$repuesto) {
                $errors['detalles'] = 'Repuesto no valido.';
                continue;
            }

            if (!ctype_digit($detalle['cantidad'])) {
                $errors['detalles'] = 'La cantidad del repuesto ' . $rowNumber . ' debe ser numerica.';
                continue;
            }

            $cantidad = (int) $detalle['cantidad'];

            if ($cantidad <= 0) {
                $errors['detalles'] = 'La cantidad de cada repuesto debe ser mayor a 0.';
                continue;
            }

            if ($cantidad > (int) $repuesto['stock']) {
                $errors['detalles'] = 'Stock insuficiente para el repuesto ' . $repuesto['nombre'] . '.';
            }
        }

        return $errors;
    }

    private function validateEditData(array $datos): array
    {
        $errors = [];
        $estadosValidos = ['Pendiente', 'En proceso', 'Finalizado'];

        if ($datos['fecha'] === '') {
            $errors['fecha'] = 'La fecha es obligatoria.';
        }

        if ($datos['tipo_servicio'] === '') {
            $errors['tipo_servicio'] = 'El tipo de servicio es obligatorio.';
        }

        if ($datos['descripcion'] === '') {
            $errors['descripcion'] = 'La descripcion es obligatoria.';
        }

        if ($datos['costo_mano_obra'] === '') {
            $errors['costo_mano_obra'] = 'El costo de mano de obra es obligatorio.';
        } elseif (!is_numeric($datos['costo_mano_obra'])) {
            $errors['costo_mano_obra'] = 'El costo de mano de obra debe ser numerico.';
        } elseif ((float) $datos['costo_mano_obra'] < 0) {
            $errors['costo_mano_obra'] = 'El costo de mano de obra no puede ser negativo.';
        }

        if (!in_array($datos['estado'], $estadosValidos, true)) {
            $errors['estado'] = 'El estado no es valido.';
        }

        return $errors;
    }

    private function emptyFormData(): array
    {
        return [
            'id_cliente' => '',
            'id_moto' => '',
            'fecha' => '',
            'tipo_servicio' => '',
            'descripcion' => '',
            'costo_mano_obra' => '0.00',
            'estado' => 'Pendiente',
            'repuesto_ids' => ['', '', ''],
            'cantidades' => ['', '', ''],
        ];
    }
}
