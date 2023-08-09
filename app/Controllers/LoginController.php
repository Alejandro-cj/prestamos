<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\UsuariosModel;

class LoginController extends BaseController
{
    private $reglas, $usuarios, $session;

    public function __construct()
    {
        helper(['form']);
        $this->usuarios = new UsuariosModel();
        $this->session = session();
    }

    public function validar()
    {
        $this->reglas = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'El campo correo es requerido',
                    'valid_email' => 'Ingrese un correo valido',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo contraseña es requerido'
                ]
            ]
        ];
        if ($this->request->is('post') && $this->validate($this->reglas)) {

            $result = $this->usuarios
                ->select('usuarios.*, r.nombre AS rol, r.permisos')
                ->join('roles AS r', 'usuarios.id_rol = r.id')
                ->where([
                    'usuarios.correo' => $this->request->getVar('email'),
                    'usuarios.estado' => '1',
                ])->first();
            if ($result != null) {
                if (password_verify($this->request->getVar('password'), $result['clave'])) {
                    $permisos = ($result['permisos'] != null) ? json_decode($result['permisos'], true) : [];
                    $datos = [
                        'id_usuario' => $result['id'],
                        'rol' => $result['rol'],
                        'nombre' => $result['nombre'],
                        'perfil' => $result['perfil'],
                        'permisos' => $permisos,
                    ];
                    $this->session->set($datos);
                    return redirect()->to(base_url('dashboard'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'HAS INICIADO SESION CORRECTAMENTE',
                    ]);
                } else {
                    return redirect()->to(base_url())->with('respuesta', [
                        'type' => 'warning',
                        'msg' => 'CONTRASEÑA INCORRECTA',
                    ]);
                }
            } else {
                return redirect()->to(base_url())->with('respuesta', [
                    'type' => 'warning',
                    'msg' => 'EL CORREO NO EXISTE',
                ]);
            }
        } else {
            $data['validator'] = $this->validator;
            return view('index', $data);
        }
    }

    public function forgot()
    {
        return view('usuarios/forgot');
    }

    public function reset()
    {
        $this->reglas = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'El campo correo es requerido',
                    'valid_email' => 'Ingrese un correo valido',
                ]
            ]
        ];
        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $correo = $this->request->getVar('email');
            $user = $this->usuarios->where([
                'correo' => $correo,
                'estado' => '1'
            ])->first();
            if (empty($user)) {
                return redirect()->to(base_url('forgot'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'EL CORREO NO EXISTE EN EL SISTEMA'
                ]);
                exit;
            } else {
                $datos = new AdminModel();
                $empresa = $datos->first();
                $token = md5(date('YmdHis'));
                $mail = new PHPMailer(true);
                try {

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
                    $mail->addAddress($correo, $user['nombre']);

                    //Attachments
                    // $mail->addAttachment('/var/tmp/file.tar.gz');
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Olvidaste tu contraseña - ' . $empresa['nombre'];
                    $mail->Body    = 'Has pedido restablecer tu contraseña, si no has sido tu omite este mensaje
                    <a href="' . base_url('restablecer/' . $token) . '">CLIC AQUI PARA CAMBIAR</a>';
                    $mail->send();
                    $this->usuarios->update($user['id'], ['token' => $token]);
                    return redirect()->to(base_url('forgot'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'CORREO ENVIADO',
                    ]);
                } catch (Exception $e) {
                    return redirect()->to(base_url('forgot'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'ERROR AL ENVIAR CORREO: ' . $mail->ErrorInfo,
                    ]);
                }
            }
        } else {
            $data['validator'] = $this->validator;
            return view('usuarios/forgot', $data);
        }
    }

    public function restablecer($token)
    {
        $consulta = $this->usuarios->where([
            'token' => $token,
            'estado' => '1'
        ])->first();
        if (empty($consulta)) {
            return redirect()->to(base_url())->with('respuesta', [
                'type' => 'danger',
                'msg' => 'TOKEN ALTERADO',
            ]);
        }else{
            $data['token'] = $token;
            return view('usuarios/restablecer', $data);
        }
        
    }

    public function restablecerPass() {
        $this->reglas = [
            'nueva' => [
                'rules' => 'required|min_length[5]'
            ],
            'confirmar' => [
                'rules' => 'required|min_length[5]|matches[nueva]'
            ]
        ];

        if ($this->request->is('put') && $this->validate($this->reglas)) {
            $consulta = $this->usuarios->where([
                'token' => $this->request->getVar('token'),
                'estado' => '1'
            ])->first();

            if (!empty($consulta)) {
                $data = $this->usuarios->update($consulta['id'], [
                    'clave' => password_hash($this->request->getVar('nueva'), PASSWORD_DEFAULT),
                    'token' => null
                ]);
                if ($data) {
                    return redirect()->to(base_url())->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'CONTRASEÑA RESTABLECIDA',
                    ]);
                } else {
                    return redirect()->to(base_url())->with('respuesta', [
                        'type' => 'danger',
                        'msg' => 'ERROR AL RESTABLECER',
                    ]);
                }
            }else{
                return redirect()->to(base_url())->with('respuesta', [
                    'type' => 'warning',
                    'msg' => 'TOKEN ALTERA',
                ]);
            }
            
        } else {
            $data['validacion'] = $this->validator;
            $data['token'] = $this->request->getVar('token');
            return view('usuarios/restablecer', $data);
        }
    }
}
