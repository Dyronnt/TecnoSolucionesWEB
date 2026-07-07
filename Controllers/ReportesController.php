<?php

require_once 'Config/Config.php';
require_once 'Models/Proyectos.php';
require_once 'Models/Clientes.php';
require_once 'Helpers/tcpdf/tcpdf.php';

class ReportesController
{
    private $proyectoModel;
    private $clienteModel;

    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL . '/index.php?controller=auth&action=login');
            exit;
        }

        $this->proyectoModel = new Proyectos();
        $this->clienteModel  = new Clientes();
    }

    // Vista principal
    public function index()
    {
        $proyectos = $this->proyectoModel->index();
        require_once 'Views/Reportes/index.php';
    }

    // REPORTE 1 - Todos los proyectos
    public function proyectos()
    {
        ob_start();
        $proyectos = $this->proyectoModel->index();

        $pdf = $this->crearPDF('L', 'Reporte de Proyectos');

        $html = '
        <table border="1" cellpadding="5">
            <thead>
                <tr style="background-color:#2c3e50; color:white;">
                    <th>Nombre</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Presupuesto</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($proyectos as $p) {
            $html .= '<tr>
                <td>' . htmlspecialchars($p['nombre']) . '</td>
                <td>' . htmlspecialchars($p['cliente_nombre']) . '</td>
                <td>' . ucfirst($p['estado']) . '</td>
                <td>' . $this->fecha($p['fecha_inicio']) . '</td>
                <td>' . $this->fecha($p['fecha_fin']) . '</td>
                <td>' . $this->monto($p['presupuesto']) . '</td>
            </tr>';
        }

        $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" align="right"><b>Total de proyectos:</b></td>
                    <td><b>' . count($proyectos) . '</b></td>
                </tr>
            </tfoot>
        </table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_proyectos_' . date('Ymd') . '.pdf', 'I');
        exit;
    }

    // REPORTE 2 - Detalle de un proyecto específico
    public function proyectoDetalle()
    {
        ob_start();
        $id       = $_GET['id'] ?? null;
        $proyecto = $this->proyectoModel->obtener($id);

        if (!$proyecto) {
            $_SESSION['error'] = "Proyecto no encontrado.";
            header('Location: ' . APP_URL . '/index.php?controller=reportes&action=index');
            exit;
        }

        $pdf = $this->crearPDF('P', 'Detalle de Proyecto');

        $html = '
        <h2>' . htmlspecialchars($proyecto['nombre']) . '</h2>
        <table border="1" cellpadding="6">
            <tr>
                <td><b>Cliente</b></td>
                <td>' . htmlspecialchars($proyecto['cliente_nombre']) . '</td>
            </tr>
            <tr>
                <td><b>Estado</b></td>
                <td>' . ucfirst($proyecto['estado']) . '</td>
            </tr>
            <tr>
                <td><b>Fecha de inicio</b></td>
                <td>' . $this->fecha($proyecto['fecha_inicio']) . '</td>
            </tr>
            <tr>
                <td><b>Fecha de fin</b></td>
                <td>' . $this->fecha($proyecto['fecha_fin']) . '</td>
            </tr>
            <tr>
                <td><b>Presupuesto</b></td>
                <td>' . $this->monto($proyecto['presupuesto']) . '</td>
            </tr>';

        if (!empty($proyecto['descripcion'])) {
            $html .= '
            <tr>
                <td><b>Descripción</b></td>
                <td>' . htmlspecialchars($proyecto['descripcion']) . '</td>
            </tr>';
        }

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('proyecto_' . $proyecto['id'] . '_' . date('Ymd') . '.pdf', 'I');
        exit;
    }

    // REPORTE 3 - Clientes con sus proyectos
    public function clientes()
    {
        ob_start();
        $clientes  = $this->clienteModel->index();
        $proyectos = $this->proyectoModel->index();

        $pdf = $this->crearPDF('P', 'Reporte de Clientes');

        $html = '';

        foreach ($clientes as $c) {
            $proy_cliente = array_filter($proyectos, function ($p) use ($c) {
                return $p['cliente_id'] == $c['id'];
            });

            $html .= '
            <h3 style="background-color:#2c3e50; color:white; padding:5px;">
                ' . htmlspecialchars($c['nombre']) . ' - RUC: ' . $c['ruc'] . '
            </h3>';

            $info = [];
            if (!empty($c['telefono']))  $info[] = 'Tel: ' . $c['telefono'];
            if (!empty($c['email']))     $info[] = 'Email: ' . $c['email'];
            if (!empty($c['direccion'])) $info[] = 'Dir: ' . $c['direccion'];
            if ($info) {
                $html .= '<p>' . implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $info) . '</p>';
            }

            if (count($proy_cliente) > 0) {
                $html .= '
                <table border="1" cellpadding="4">
                    <thead>
                        <tr style="background-color:#aed6f1;">
                            <th>Proyecto</th>
                            <th>Estado</th>
                            <th>Fecha Inicio</th>
                            <th>Presupuesto</th>
                        </tr>
                    </thead>
                    <tbody>';

                foreach ($proy_cliente as $p) {
                    $html .= '<tr>
                        <td>' . htmlspecialchars($p['nombre']) . '</td>
                        <td>' . ucfirst($p['estado']) . '</td>
                        <td>' . $this->fecha($p['fecha_inicio']) . '</td>
                        <td>' . $this->monto($p['presupuesto']) . '</td>
                    </tr>';
                }

                $html .= '</tbody></table><br>';
            } else {
                $html .= '<p><i>Sin proyectos registrados.</i></p>';
            }
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_clientes_' . date('Ymd') . '.pdf', 'I');
        exit;
    }

    // ─── Helpers privados ───────────────────────────────────────────

    private function crearPDF($orientacion, $titulo)
    {
        $pdf = new TCPDF($orientacion, 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(APP_NAME);
        $pdf->SetAuthor(APP_NAME);
        $pdf->SetTitle($titulo);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // Encabezado manual
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(44, 62, 80); // #2c3e50
        $pdf->Cell(0, 8, APP_NAME, 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 6, $titulo . '  -  Generado el ' . date('d/m/Y H:i'), 0, 1, 'L');

        $pdf->SetDrawColor(44, 62, 80);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(15, $pdf->GetY(), $pdf->getPageWidth() - 15, $pdf->GetY());
        $pdf->Ln(5);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 10);

        return $pdf;
    }

    private function fecha($fecha)
    {
        if (empty($fecha)) return '-';
        return date('d/m/Y', strtotime($fecha));
    }

    private function monto($valor)
    {
        if ($valor === null || $valor === '') return '-';
        return 'S/ ' . number_format($valor, 2);
    }
}
