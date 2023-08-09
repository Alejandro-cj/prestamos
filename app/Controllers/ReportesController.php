<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\ClientesModel;
use App\Models\PrestamosModel;

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportesController extends BaseController
{
    private $prestamos, $empresa, $session, $clientes;
    public function __construct()
    {
        $this->prestamos = new PrestamosModel();
        $this->empresa = new AdminModel();
        $this->clientes = new ClientesModel();
        $this->session = session();
        helper(['fecha']);
    }
    public function reportesPdf($url)
    {
        if (!verificar('pdf prestamos', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['prestamos'] = $this->filtroReportes($url);

        for ($i = 0; $i < count($data['prestamos']); $i++) {
            $result = $this->clientes->select('nombre, apellido')->where('id', $data['prestamos'][$i]['id_cliente'])->first();
            $data['prestamos'][$i]['cliente'] = $result['nombre'] . ' ' . $result['apellido'];
        }
        $data['empresa'] = $this->empresa->first();
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        ob_start();
        echo view('reportes/prestamos', $data);
        $html = ob_get_clean();

        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', ['Attachment' => false]);
    }

    public function reportesExcel($url)
    {
        if (!verificar('excel prestamos', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        
        $results = $this->filtroReportes($url);

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('Angel')->setTitle('Prestamos');

        $spreadsheet->setActiveSheetIndex(0);

        $spreadsheet->getActiveSheet()->getStyle('A1:E1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF0000');

        $hojaActiva = $spreadsheet->getActiveSheet();
        $hojaActiva->getColumnDimension('A')->setWidth('50');
        $hojaActiva->getColumnDimension('B')->setWidth('15');
        $hojaActiva->getColumnDimension('C')->setWidth('20');
        $hojaActiva->getColumnDimension('D')->setWidth('20');
        $hojaActiva->getColumnDimension('E')->setWidth('40');


        $hojaActiva->setCellValue('A1', 'CLIENTE');
        $hojaActiva->setCellValue('B1', 'IMPORTE');
        $hojaActiva->setCellValue('C1', 'MODALIDAD');
        $hojaActiva->setCellValue('D1', 'TASA INTERES');
        $hojaActiva->setCellValue('E1', 'F. VENCIMIENTO');

        $fila = 2;
        foreach ($results as $prestamo) {
            $result = $this->clientes->select('nombre, apellido')->where('id', $prestamo['id_cliente'])->first();
            $hojaActiva->setCellValue('A' . $fila, $result['nombre'] . ' ' . $result['apellido']);
            $hojaActiva->setCellValue('B' . $fila, $prestamo['importe']);
            $hojaActiva->setCellValue('C' . $fila, $prestamo['modalidad']);
            $hojaActiva->setCellValue('D' . $fila, $prestamo['tasa_interes']);
            $hojaActiva->setCellValue('E' . $fila, fechaPerzo($prestamo['fecha_venc']));
            $fila++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="prestamos.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

    public function filtroReportes($url) {
        $id_usuario = $this->session->id_usuario;
        if ($url === 'dia') {
            $where = "TO_DAYS(fecha) = TO_DAYS(NOW()) AND id_usuario = $id_usuario AND estado = 1";
        } else if ($url === 'semana') {
            $where = "DATE_SUB(CURDATE(), INTERVAL 7 DAY) < date(fecha) AND id_usuario = $id_usuario AND estado = 1";
        } else if ($url === 'ultimos') {
            $where = "DATE_SUB(CURDATE(), INTERVAL 30 DAY) < date(fecha) AND id_usuario = $id_usuario AND estado = 1";
        } else if ($url === 'anterior') {
            $inicioMes = date('Y-m-01', strtotime('last month'));
            $finMes = date('Y-m-t', strtotime('last month'));
            $where = "fecha >= '$inicioMes' AND fecha <= '$finMes' AND id_usuario = $id_usuario AND estado = 1";
        } else {
            $where = "DATE_FORMAT(fecha, '%Y%m') = DATE_FORMAT(CURDATE(), '%Y%m') AND id_usuario = $id_usuario AND estado = 1";
        }
        $results = $this->prestamos->where($where)->findAll();
        return $results;
    }
}
