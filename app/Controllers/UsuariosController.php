<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RolesModel;
use App\Models\UsuariosModel;

class UsuariosController extends BaseController
{
    private $usuarios, $roles, $reglas, $session;
    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->roles = new RolesModel();
        helper(['form']);
        $this->session = session();
    }
    public function index()
    {
        if (!verificar('listar usuarios', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'usuario';
        return view('usuarios/index', $data);
    }

    public function listar()
    {
        $this->usuarios->select('usuarios.id, usuarios.nombre, usuarios.apellido, usuarios.telefono, usuarios.correo, usuarios.direccion, usuarios.estado, roles.nombre AS rol');
        $data = $this->usuarios->join('roles', 'usuarios.id_rol = roles.id')->where('usuarios.estado', '1')->findAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function new()
    {
        if (!verificar('nuevo usuario', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['roles'] = $this->roles->where('estado', '1')->findAll();
        $data['active'] = 'usuario';
        return view('usuarios/nuevo', $data);
    }

    public function create()
    {
        if (verificar('nuevo usuario', $this->session->permisos)) {
            $this->reglas = [
                'nombre' => [
                    'rules' => 'required'
                ],
                'apellido' => [
                    'rules' => 'required'
                ],
                'telefono' => [
                    'rules' => 'required|min_length[9]|is_unique[usuarios.telefono]'
                ],
                'correo' => [
                    'rules' => 'required|valid_email|is_unique[usuarios.correo]'
                ],
                'direccion' => [
                    'rules' => 'required'
                ],
                'rol' => [
                    'rules' => 'required'
                ],
                'clave' => [
                    'rules' => 'required|min_length[5]'
                ],
                'confirmar' => [
                    'rules' => 'required|min_length[5]|matches[clave]'
                ]
            ];

            if ($this->request->is('post') && $this->validate($this->reglas)) {
                $data = $this->usuarios->insert([
                    'nombre' => $this->request->getVar('nombre'),
                    'apellido' => $this->request->getVar('apellido'),
                    'telefono' => $this->request->getVar('telefono'),
                    'correo' => $this->request->getVar('correo'),
                    'direccion' => $this->request->getVar('direccion'),
                    'clave' => password_hash($this->request->getVar('clave'), PASSWORD_DEFAULT),
                    'id_rol' => $this->request->getVar('rol'),
                ]);
                if ($data > 0) {
                    return redirect()->to(base_url('usuarios'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'USUARIO REGISTRADO',
                    ]);
                } else {
                    return redirect()->to(base_url('usuarios'))->with('respuesta', [
                        'type' => 'danger',
                        'msg' => 'ERROR AL REGISTRAR',
                    ]);
                }
            } else {
                $data['validacion'] = $this->validator;
                $data['roles'] = $this->roles->where('estado', '1')->findAll();
                $data['rol'] = $this->request->getVar('rol');
                $data['active'] = 'usuario';
                return view('usuarios/nuevo', $data);
            }
        } else {
            return view('permisos');
        }
    }

    public function delete($idUsuario)
    {
        if ($this->request->is('delete') && verificar('eliminar usuario', $this->session->permisos)) {
            //$data = $this->usuarios->delete($idUsuario);
            $data = $this->usuarios->update($idUsuario, ['estado' => '0']);
            if ($data) {
                return redirect()->to(base_url('usuarios'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'USUARIO DADO DE BAJA',
                ]);
            } else {
                return redirect()->to(base_url('usuarios'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        } else {
            return view('permisos');
        }
    }

    public function edit($idUsuario)
    {
        if (!verificar('editar usuario', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['roles'] = $this->roles->where('estado', '1')->findAll();
        $data['usuario'] = $this->usuarios->where('id', $idUsuario)->first();
        $data['active'] = 'usuario';
        return view('usuarios/edit', $data);
    }

    public function update($idUsuario)
    {
        if (verificar('editar usuario', $this->session->permisos)) {
            $this->reglas = [
                'id_usuario'    => 'is_natural_no_zero',
                'nombre' => [
                    'rules' => 'required'
                ],
                'apellido' => [
                    'rules' => 'required'
                ],
                'telefono' => [
                    'rules' => 'required|min_length[9]|is_unique[usuarios.telefono,id,{id_usuario}]'
                ],
                'correo' => [
                    'rules' => 'required|valid_email|is_unique[usuarios.correo,id,{id_usuario}]'
                ],
                'direccion' => [
                    'rules' => 'required'
                ],
                'rol' => [
                    'rules' => 'required'
                ]
            ];

            if ($this->request->is('put') && $this->validate($this->reglas)) {
                $data = $this->usuarios->update($idUsuario, [
                    'nombre' => $this->request->getVar('nombre'),
                    'apellido' => $this->request->getVar('apellido'),
                    'telefono' => $this->request->getVar('telefono'),
                    'correo' => $this->request->getVar('correo'),
                    'direccion' => $this->request->getVar('direccion'),
                    'id_rol' => $this->request->getVar('rol')
                ]);
                if ($data > 0) {
                    return redirect()->to(base_url('usuarios'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'USUARIO MODIFICADO',
                    ]);
                } else {
                    return redirect()->to(base_url('usuarios'))->with('respuesta', [
                        'type' => 'danger',
                        'msg' => 'ERROR AL MODIFICAR',
                    ]);
                }
            } else {
                $data['validacion'] = $this->validator;
                $data['roles'] = $this->roles->where('estado', '1')->findAll();
                $data['rol'] = $this->request->getVar('rol');
                $data['usuario'] = $this->usuarios->where('id', $idUsuario)->first();
                $data['active'] = 'usuario';
                return view('usuarios/edit', $data);
            }
        } else {
            return view('permisos');
        }
    }

    public function profile()
    {
        $data['usuario'] = $this->usuarios->where('id', $this->session->id_usuario)->first();
        $data['active'] = 'usuario';
        return view('usuarios/perfil', $data);
    }

    public function saveprofile(){
        $this->reglas = [
            'id_usuario'    => 'is_natural_no_zero',
            'nombre' => [
                'rules' => 'required'
            ],
            'apellido' => [
                'rules' => 'required'
            ],
            'telefono' => [
                'rules' => 'required|min_length[9]|is_unique[usuarios.telefono,id,{id_usuario}]'
            ],
            'correo' => [
                'rules' => 'required|valid_email|is_unique[usuarios.correo,id,{id_usuario}]'
            ],
            'direccion' => [
                'rules' => 'required'
            ]
        ];

        if ($this->request->is('put') && $this->validate($this->reglas)) {
            $img = $this->request->getFile('foto');
            $imgVerify = false;
            if (!empty($img->getName()) && $img->getClientMimeType() === 'image/png') {
                $imgVerify = true;
            } 
            $data = $this->usuarios->update($this->session->id_usuario, [
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'telefono' => $this->request->getVar('telefono'),
                'correo' => $this->request->getVar('correo'),
                'direccion' => $this->request->getVar('direccion'),
                'perfil' => ($imgVerify) ? $this->session->id_usuario . '.png' : null
            ]);
            
            if ($data) {
                if ($imgVerify) {
                    if (!$img->hasMoved()) {
                        $ruta = 'assets/img/users/' . $this->session->id_usuario . '.png';
                        if (file_exists($ruta)) {
                            unlink($ruta);
                        }
                        $img->move('assets/img/users', $this->session->id_usuario . '.png');
                    }
                }
                return redirect()->to(base_url('usuarios/profile'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'DATOS MODIFICADO',
                ]);
            } else {
                return redirect()->to(base_url('usuarios/profile'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL MODIFICAR',
                ]);
            }
        } else {
            $data['validacion'] = $this->validator;
            $data['usuario'] = $this->usuarios->where('id', $this->session->id_usuario)->first();
            $data['active'] = 'usuario';
            return view('usuarios/perfil', $data);
        }
    }

    public function cambiarClave() {
        $this->reglas = [
            'actual' => [
                'rules' => 'required'
            ],
            'nueva' => [
                'rules' => 'required|min_length[5]'
            ],
            'confirmar' => [
                'rules' => 'required|min_length[5]|matches[nueva]'
            ]
        ];

        if ($this->request->is('put') && $this->validate($this->reglas)) {
            $consulta = $this->usuarios->where('id', $this->session->id_usuario)->first();
            if (password_verify($this->request->getVar('actual'), $consulta['clave'])) {
                $data = $this->usuarios->update($this->session->id_usuario, [
                    'clave' => password_hash($this->request->getVar('nueva'), PASSWORD_DEFAULT)
                ]);
                if ($data) {
                    return redirect()->to(base_url('usuarios/profile'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'CONTRASEÑA MODIFICADO',
                    ]);
                } else {
                    return redirect()->to(base_url('usuarios/profile'))->with('respuesta', [
                        'type' => 'danger',
                        'msg' => 'ERROR AL MODIFICAR',
                    ]);
                }
            }else{
                return redirect()->to(base_url('usuarios/profile'))->with('respuesta', [
                    'type' => 'warning',
                    'msg' => 'CONTRASEÑA ACTUAL INCORRECTA',
                ]);
            }
            
        } else {
            $data['validacion'] = $this->validator;
            $data['usuario'] = $this->usuarios->where('id', $this->session->id_usuario)->first();
            $data['active'] = 'usuario';
            return view('usuarios/perfil', $data);
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url());
    }
}
