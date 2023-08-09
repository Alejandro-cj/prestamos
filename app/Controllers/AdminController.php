<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\CajasModel;
use App\Models\ClientesModel;
use App\Models\PrestamosModel;
use App\Models\UsuariosModel;
use Config\Database;
use ZipArchive;

class AdminController extends BaseController
{
    private $admin, $session, $usuarios, $clientes, $prestamos, $cajas;

    public function __construct()
    {
        helper(['form']);
        $this->session = session();
        $this->admin = new AdminModel();
        $this->prestamos = new PrestamosModel();
    }

    public function index()
    {
        if (!verificar('actualizar empresa', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'config';
        $data['admin'] = $this->admin->first();
        return view('admin/index', $data);
    }

    public function dashboard()
    {
        $this->usuarios = new UsuariosModel();
        $this->clientes = new ClientesModel();
        $this->cajas = new CajasModel();
        $data['usuarios'] = $this->usuarios->where('estado', '1')->countAllResults();
        $data['clientes'] = $this->clientes->where('estado', '1')->countAllResults();
        $data['prestamos'] = $this->prestamos->where('estado', '1')->countAllResults();
        $data['cajas'] = $this->cajas->calcularMovimientos($this->session->id_usuario);
        $data['active'] = 'dashboard';
        return view('admin/home', $data);
    }

    public function update($id)
    {
        if ($this->request->is('put')) {
            $img = $this->request->getFile('logo');
            $data = [
                'id' => $id,
                'identidad' => $this->request->getVar('identidad'),
                'nombre' => $this->request->getVar('nombre'),
                'telefono' => $this->request->getVar('telefono'),
                'correo' => $this->request->getVar('correo'),
                'direccion' => $this->request->getVar('direccion'),
                'mensaje' => $this->request->getVar('mensaje'),
                'tasa_interes' => $this->request->getVar('tasa_interes'),
                'cuotas' => $this->request->getVar('cuotas')
            ];
            if ($this->admin->update($id, $data) === false) {
                $data['admin'] = $this->admin->first();
                $data['errors'] = $this->admin->errors();
                $data['active'] = 'config';
                return view('admin/index', $data);
            }
            if (!empty($img->getName()) && $img->getClientMimeType() === 'image/png') {
                if (!$img->hasMoved()) {
                    $ruta = 'assets/img/logo.png';
                    if (file_exists($ruta)) {
                        unlink($ruta);
                    }
                    $img->move('assets/img', 'logo.png');
                }
            }

            return redirect()->to(base_url('admin'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'DATOS MODIFICADO',
            ]);
        }
    }

    public function prestamosMes($anio)
    {
        $desde = $anio . '-01-01 00:00:00';
        $hasta = $anio . '-12-31 23:59:59';
        $id_usuario = $this->session->id_usuario;
        $where = "fecha BETWEEN '$desde' AND '$hasta' AND estado = 1 AND id_usuario = $id_usuario";
        $data['total'] = $this->prestamos->select("
        SUM(IF(MONTH(fecha) = 1, importe, 0)) AS ene,
        SUM(IF(MONTH(fecha) = 2, importe, 0)) AS feb,
        SUM(IF(MONTH(fecha) = 3, importe, 0)) AS mar,
        SUM(IF(MONTH(fecha) = 4, importe, 0)) AS abr,
        SUM(IF(MONTH(fecha) = 5, importe, 0)) AS may,
        SUM(IF(MONTH(fecha) = 6, importe, 0)) AS jun,
        SUM(IF(MONTH(fecha) = 7, importe, 0)) AS jul,
        SUM(IF(MONTH(fecha) = 8, importe, 0)) AS ago,
        SUM(IF(MONTH(fecha) = 9, importe, 0)) AS sep,
        SUM(IF(MONTH(fecha) = 10, importe, 0)) AS oct,
        SUM(IF(MONTH(fecha) = 11, importe, 0)) AS nov,
        SUM(IF(MONTH(fecha) = 12, importe, 0)) AS dic")
            ->where($where)->first();
        //calcular maximo
        $data['max'] = $this->prestamos->selectMax('importe')->where($where)->first();
        echo json_encode($data);
        die();
    }

    public function createBackup()
    {
        if (verificar('backup', $this->session->permisos)) {
            // Configuración de la base de datos
            $db = Database::connect();
            $tables = $db->listTables();

            // Generar el respaldo
            $backupData = '';
            foreach ($tables as $table) {
                $query = $db->query("SELECT * FROM $table");
                $result = $query->getResultArray();

                if (!empty($result)) {
                    $backupData .= "-- --------------------------------------------------------\n";
                    $backupData .= "-- Estructura de tabla para $table\n";
                    $backupData .= "-- --------------------------------------------------------\n\n";

                    $schemaQuery = $db->query("SHOW CREATE TABLE $table");
                    $schema = $schemaQuery->getRow()->{'Create Table'};
                    $backupData .= $schema . ";\n\n";

                    $backupData .= "-- --------------------------------------------------------\n";
                    $backupData .= "-- Datos de tabla para $table\n";
                    $backupData .= "-- --------------------------------------------------------\n\n";

                    foreach ($result as $row) {
                        $values = array_map(function ($value) {
                            return "'" . str_replace("'", "''", $value) . "'";
                        }, $row);

                        $backupData .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
                    }

                    $backupData .= "\n";
                }
            }

            // Guardar el respaldo en un archivo
            $backupFilename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            if (!file_exists(WRITEPATH . 'backups')) {
                mkdir(WRITEPATH . 'backups');
            }
            $backupPath = WRITEPATH . 'backups/' . $backupFilename;

            if (!empty($backupData)) {
                if (file_put_contents($backupPath, $backupData) !== false) {
                    if ($this->crearZip($backupPath)) {
                        unlink($backupPath);
                        // Ruta y nombre del archivo a descargar
                        $filePath = './backup.zip';
                        $fileName = 'archivo.zip';

                        // Verifica si el archivo existe
                        if (file_exists($filePath)) {
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="' . $fileName . '"');
                            header('Content-Length: ' . filesize($filePath));

                            // Lee y envía el contenido del archivo
                            readfile($filePath);
                            exit;
                        } else {
                            return redirect()->to(base_url('dashboard'))->with('respuesta', [
                                'type' => 'danger',
                                'msg' => 'EL ARCHIVO NO SE ENCONTRO'
                            ]);
                        }
                    } else {
                        return redirect()->to(base_url('dashboard'))->with('respuesta', [
                            'type' => 'danger',
                            'msg' => 'ERROR AL CREAR ZIP'
                        ]);
                    }
                } else {
                    return redirect()->to(base_url('dashboard'))->with('respuesta', [
                        'type' => 'danger',
                        'msg' => 'ERROR AL CREAR RESPALDO'
                    ]);
                }
            } else {
                return redirect()->to(base_url('dashboard'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'NO SE ENCONTRARON DATOS PARA RESPALDAR',
                ]);
            }
        } else {
            echo view("permisos");
        }
    }

    public function crearZip($ruta)
    {
        $zip = new ZipArchive();

        // Nombre y ruta del archivo ZIP que se va a crear
        $zipFilename = 'backup.zip';

        // Abre el archivo ZIP en modo de creación
        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Agrega el archivo PHP al archivo ZIP
            $zip->addFile($ruta, basename($ruta));

            // Cierra el archivo ZIP
            $zip->close();

            return true;
        } else {
            return false;
        }
    }
}
