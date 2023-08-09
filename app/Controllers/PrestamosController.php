<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\CajasModel;
use App\Models\ClientesModel;
use App\Models\DetPrestamoModel;
use App\Models\PrestamosModel;

// reference the Dompdf namespace
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PrestamosController extends BaseController
{
    private $empresa, $clientes, $prestamos,
        $detalle, $session, $reglas, $cajas;
    public function __construct()
    {
        helper(['form', 'fecha']);
        $this->empresa = new AdminModel();
        $this->clientes = new ClientesModel();
        $this->prestamos = new PrestamosModel();
        $this->detalle = new DetPrestamoModel();
        $this->cajas = new CajasModel();
        $this->session = session();
    }

    public function index()
    {
        if (!verificar('nuevo prestamo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['empresa'] = $this->empresa->first();
        $data['active'] = 'prestamo';
        return view('prestamos/nuevo', $data);
    }

    public function buscarCliente()
    {
        if ($this->request->is('get') && !empty($this->request->getVar('term'))) {
            $data = $this->clientes->like('num_identidad', $this->request->getVar('term'))
                ->where('estado', '1')->findAll(10);
            $result = array();
            foreach ($data as $cliente) {
                $datos['id'] = $cliente['id'];
                $datos['value'] = $cliente['num_identidad'] . ' - ' . $cliente['nombre'] . ' ' . $cliente['apellido'];
                array_push($result, $datos);
            }
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function create()
    {
        if ($this->request->is('post') && verificar('nuevo prestamo', $this->session->permisos)) {
            $fecha = date('Y-m-d');
            //calcular vencimiento
            if ($this->request->getVar('modalidad') === 'DIARIO') {
                $fecha_venc = date('Y-m-d', strtotime($fecha . '+1 days'));
            } else if ($this->request->getVar('modalidad') === 'SEMANAL') {
                $fecha_venc = date('Y-m-d', strtotime($fecha . '+7 days'));
            } else if ($this->request->getVar('modalidad') === 'MENSUAL') {
                $fecha_venc = date('Y-m-d', strtotime($fecha . '+1 month'));
            } else {
                $fecha_venc = date('Y-m-d', strtotime($fecha . '+1 year'));
            }
            $data = [
                'cliente' => $this->request->getVar('cliente'),
                'importe' => $this->request->getVar('importe_credito'),
                'modalidad' => $this->request->getVar('modalidad'),
                'tasa_interes' => $this->request->getVar('tasa_interes'),
                'cuotas' => $this->request->getVar('cuotas'),
                'fecha' => date('Y-m-d H:i:s'),
                'fecha_venc' => $fecha_venc,
                'id_cliente' => $this->request->getVar('id_cliente'),
                'id_usuario' => $this->session->id_usuario
            ];
            //verificar cliente
            $sqlCliente = $this->prestamos->where([
                'id_cliente' => $this->request->getVar('id_cliente'),
                'estado' => '1',
            ])->first();

            $verificarSaldo = $this->cajas->calcularMovimientos($this->session->id_usuario);
            if ($verificarSaldo['saldo'] >= $this->request->getVar('importe_credito')) {
                if (empty($sqlCliente)) {
                    if ($this->prestamos->insert($data) === false) {
                        $data['errors'] = $this->prestamos->errors();
                        $data['empresa'] = $this->empresa->first();
                        $data['modalidad'] = $this->request->getVar('modalidad');
                        $data['cuotas'] = $this->request->getVar('cuotas');
                        $data['active'] = 'prestamo';
                        return view('prestamos/nuevo', $data);
                    }
                    $prestamo = $this->prestamos->getInsertID();
                    if ($prestamo > 0) {
                        //calcular ganancia
                        $ganancia = $this->request->getVar('importe_credito')
                            * ($this->request->getVar('tasa_interes') / 100);
                        //calcular importe cuota
                        $importe_cuota = ($this->request->getVar('importe_credito')
                            / $this->request->getVar('cuotas'))
                            + ($ganancia / $this->request->getVar('cuotas'));

                        for ($i = 1; $i <= $this->request->getVar('cuotas'); $i++) {
                            $presDetalle = $this->detalle->insert([
                                'cuota' => $i,
                                'fecha_venc' => $fecha_venc,
                                'importe_cuota' => $importe_cuota,
                                'id_prestamo' => $prestamo,
                            ]);
                            //consulta de vencimiento
                            $consulta = $this->detalle->where('id', $presDetalle)->first();
                            //calcular vencimiento
                            if ($this->request->getVar('modalidad') === 'DIARIO') {
                                $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 days'));
                            } else if ($this->request->getVar('modalidad') === 'SEMANAL') {
                                $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+7 days'));
                            } else if ($this->request->getVar('modalidad') === 'MENSUAL') {
                                $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 month'));
                            } else {
                                $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 year'));
                            }
                        }
                        return redirect()->to(base_url('prestamos/' . $prestamo . '/detail'))->with('respuesta', [
                            'type' => 'success',
                            'msg' => 'PRESTAMO REGISTRADO',
                        ]);
                    } else {
                        return redirect()->to(base_url('prestamos'))->with('respuesta', [
                            'type' => 'warning',
                            'msg' => 'ERROR AL REALIZAR PRESTAMO',
                        ]);
                    }
                } else {
                    return redirect()->to(base_url('prestamos'))->with('respuesta', [
                        'type' => 'warning',
                        'msg' => 'YA TIENES UN PRESTAMO PENDIENTE',
                    ]);
                }
            } else {
                return redirect()->to(base_url('prestamos'))->with('respuesta', [
                    'type' => 'warning',
                    'msg' => 'SALDO INSUFICIENTE',
                ]);
            }
        }else{
            return view('permisos');
        }
    }

    public function detail($id)
    {
        if (!verificar('ver prestamo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['prestamo'] = $this->prestamos
            ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, u.nombre AS usuario, u.apellido AS user_apellido')
            ->join('clientes AS c', 'prestamos.id_cliente = c.id')
            ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
            ->where('prestamos.id', $id)->first();

        $data['detalles'] = $this->detalle->where('id_prestamo', $id)->findAll();
        $data['active'] = 'prestamo';
        return view('prestamos/detail', $data);
    }

    public function reporte($id)
    {
        if (!verificar('ver prestamo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['prestamo'] = $this->prestamos
            ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, c.direccion, u.nombre AS usuario, u.apellido AS user_apellido')
            ->join('clientes AS c', 'prestamos.id_cliente = c.id')
            ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
            ->where('prestamos.id', $id)->first();

        $data['detalles'] = $this->detalle->where('id_prestamo', $id)->findAll();
        $data['empresa'] = $this->empresa->first();
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        ob_start();
        echo view('prestamos/contrato', $data);
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

    public function update($id)
    {
        if ($this->request->is('put') && verificar('abono prestamo', $this->session->permisos)) {
            $consulta = $this->detalle
                ->select('detalle_prestamos.id_prestamo, detalle_prestamos.fecha_venc, p.modalidad')
                ->join('prestamos AS p', 'detalle_prestamos.id_prestamo = p.id')
                ->where('detalle_prestamos.id', $id)->first();

            $data = $this->detalle->update($id, ['estado' => '0']);

            if ($data) {
                //calcular vencimiento
                if ($consulta['modalidad'] === 'DIARIO') {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 days'));
                } else if ($consulta['modalidad'] === 'SEMANAL') {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+7 days'));
                } else if ($consulta['modalidad'] === 'MENSUAL') {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 month'));
                } else {
                    $fecha_venc = date('Y-m-d', strtotime($consulta['fecha_venc'] . '+1 year'));
                }
                //comprobar las cuotas
                $datos = $this->detalle->where([
                    'id_prestamo' => $consulta['id_prestamo'],
                    'estado' => '1'
                ])->first();
                if (!empty($datos)) {
                    $this->prestamos->update($consulta['id_prestamo'], ['fecha_venc' => $fecha_venc]);
                    return redirect()->to(base_url('prestamos/' . $consulta['id_prestamo'] . '/detail'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'ESTADO CAMBIADO',
                    ]);
                } else {
                    $this->prestamos->update($consulta['id_prestamo'], ['estado' => '2']);
                    return redirect()->to(base_url('prestamos/' . $consulta['id_prestamo'] . '/detail'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'PRESTAMO FINALIZADO',
                    ]);
                }
            } else {
                return redirect()->to(base_url('prestamos/' . $consulta['id_prestamo'] . '/detail'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL CAMBIAR EL ESTADO',
                ]);
            }
        }else{
            return view('permisos');
        }
    }

    public function enviarCorreo()
    {
        $this->reglas = [
            'correo' => [
                'rules' => 'required|valid_email'
            ],
            'mensaje' => [
                'rules' => 'required'
            ]
        ];
        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $correo = $this->request->getVar('correo');
            $mail = new PHPMailer(true);
            try {
                $empresa = $this->empresa->first();
                $cliente = $this->clientes->where('correo', $correo)->first();
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'lovenaju2@gmail.com';                     //SMTP username
                $mail->Password   = 'xgrcrehtwxmrhmdf';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom($empresa['correo'], $empresa['nombre']);
                $mail->addAddress($correo, $cliente['nombre']);

                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Contrato de prestamo - ' . $empresa['nombre'];
                $mail->Body    = $this->request->getVar('mensaje');
                $mail->send();
                return redirect()->to(base_url('prestamos/' . $this->request->getVar('id_prestamo') . '/detail'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'CORREO ENVIADO',
                ]);
            } catch (Exception $e) {
                return redirect()->to(base_url('prestamos/' . $this->request->getVar('id_prestamo') . '/detail'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'ERROR AL ENVIAR CORREO: ' . $mail->ErrorInfo,
                ]);
            }
        } else {
            $data['validator'] = $this->validator;

            $data['prestamo'] = $this->prestamos
                ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, u.nombre AS usuario, u.apellido AS user_apellido')
                ->join('clientes AS c', 'prestamos.id_cliente = c.id')
                ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
                ->where('prestamos.id', $this->request->getVar('id_prestamo'))->first();

            $data['detalles'] = $this->detalle->where('id_prestamo', $this->request->getVar('id_prestamo'))->findAll();
            $data['active'] = 'prestamo';
            return view('prestamos/detail', $data);
        }
    }

    public function historial()
    {
        if (!verificar('historial prestamos', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'prestamo';
        return view('prestamos/historial', $data);
    }

    public function listHistorial()
    {
        if ($this->request->is('get')) {
            $data = $this->prestamos->select('prestamos.*, c.identidad, c.num_identidad, c.nombre, c.apellido, u.nombre AS usuario')
                //->from('prestamos AS p', true)
                ->join('clientes AS c', 'prestamos.id_cliente = c.id')
                ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
                ->where('prestamos.estado != 0')->findAll();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['vencimiento'] = fechaPerzo($data[$i]['fecha_venc']);
                $ganancia = $this->detalle->selectSum('importe_cuota')->where([
                    'estado' => '0',
                    'id_prestamo' => $data[$i]['id']
                ])->first();
                $data[$i]['ganancia'] = ($ganancia['importe_cuota'] != null) ? number_format($ganancia['importe_cuota'] - $data[$i]['importe'], 2) : '-' . number_format($data[$i]['importe'], 2);
                $data[$i]['gd'] = ($ganancia['importe_cuota'] != null) ? $ganancia['importe_cuota'] - $data[$i]['importe'] : '-' . $data[$i]['importe'];
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function delete($id)
    {
        if ($this->request->is('delete') && verificar('eliminar prestamo', $this->session->permisos)) {
            //$data = $this->prestamos->delete($id);
            $data = $this->prestamos->update($id, ['estado' => '0']);
            if ($data) {
                return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'PRESTAMO ELIMINADO',
                ]);
            } else {
                return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        }else{
            return view('permisos');
        }
    }
}
