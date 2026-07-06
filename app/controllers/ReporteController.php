<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Repuesto.php';
require_once __DIR__ . '/../models/Mantenimiento.php';

class ReporteController extends BaseController
{
    private Cliente $clienteModel;
    private Repuesto $repuestoModel;
    private Mantenimiento $mantenimientoModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->clienteModel = new Cliente();
        $this->repuestoModel = new Repuesto();
        $this->mantenimientoModel = new Mantenimiento();
    }

    public function index(): void
    {
        $clientes = $this->clienteModel->obtenerTodos();
        $resultados = [
            'fechas' => [],
            'cliente' => [],
            'estado' => [],
            'bajo_stock' => $this->repuestoModel->obtenerBajoStock(),
        ];
        $filtros = [
            'fecha_inicio' => '',
            'fecha_fin' => '',
            'id_cliente' => '',
            'estado' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = trim($_POST['reporte_accion'] ?? '');

            if ($accion === 'por_fechas') {
                $fechaInicio = trim($_POST['fecha_inicio'] ?? '');
                $fechaFin = trim($_POST['fecha_fin'] ?? '');
                $filtros['fecha_inicio'] = $fechaInicio;
                $filtros['fecha_fin'] = $fechaFin;

                if ($fechaInicio === '' || $fechaFin === '') {
                    $this->setFlash('warning', 'Validacion', 'Debe ingresar la fecha de inicio y la fecha fin.');
                } elseif ($fechaInicio > $fechaFin) {
                    $this->setFlash('warning', 'Fechas invalidas', 'La fecha inicio no puede ser mayor que la fecha fin.');
                } else {
                    $resultados['fechas'] = $this->mantenimientoModel->obtenerPorRangoFechas($fechaInicio, $fechaFin);
                    $this->setFlash(empty($resultados['fechas']) ? 'info' : 'success', empty($resultados['fechas']) ? 'Sin resultados' : 'Consulta correcta', empty($resultados['fechas']) ? 'No se encontraron mantenimientos en ese rango de fechas.' : 'Reporte por fechas generado correctamente.');
                }
            }

            if ($accion === 'por_cliente') {
                $idCliente = trim($_POST['id_cliente'] ?? '');
                $filtros['id_cliente'] = $idCliente;

                if ($idCliente === '') {
                    $this->setFlash('warning', 'Validacion', 'Debe seleccionar un cliente.');
                } elseif (!$this->clienteModel->obtenerPorId((int) $idCliente)) {
                    $this->setFlash('error', 'Cliente no valido', 'El cliente seleccionado no existe.');
                } else {
                    $resultados['cliente'] = $this->mantenimientoModel->obtenerPorCliente((int) $idCliente);
                    $this->setFlash(empty($resultados['cliente']) ? 'info' : 'success', empty($resultados['cliente']) ? 'Sin resultados' : 'Consulta correcta', empty($resultados['cliente']) ? 'No se encontraron mantenimientos para el cliente seleccionado.' : 'Reporte por cliente generado correctamente.');
                }
            }

            if ($accion === 'por_estado') {
                $estado = trim($_POST['estado'] ?? '');
                $filtros['estado'] = $estado;
                $estadosValidos = ['Pendiente', 'En proceso', 'Finalizado'];

                if ($estado === '') {
                    $this->setFlash('warning', 'Validacion', 'Debe seleccionar un estado.');
                } elseif (!in_array($estado, $estadosValidos, true)) {
                    $this->setFlash('error', 'Estado no valido', 'El estado seleccionado no es valido.');
                } else {
                    $resultados['estado'] = $this->mantenimientoModel->obtenerPorEstado($estado);
                    $this->setFlash(empty($resultados['estado']) ? 'info' : 'success', empty($resultados['estado']) ? 'Sin resultados' : 'Consulta correcta', empty($resultados['estado']) ? 'No se encontraron mantenimientos para el estado seleccionado.' : 'Reporte por estado generado correctamente.');
                }
            }
        }

        $this->render('reportes/index', [
            'title' => 'Reportes',
            'clientes' => $clientes,
            'resultados' => $resultados,
            'filtros' => $filtros,
        ]);
    }
}
