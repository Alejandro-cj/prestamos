<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermisosModel;
use App\Models\RolesModel;

class RolesController extends BaseController
{
    private $roles, $permisos, $session;
    public function __construct()
    {
        $this->roles = new RolesModel();
        $this->permisos = new PermisosModel();
        helper(['form', 'buscar']);
        $this->session = session();
    }
    public function index()
    {
        if (!verificar('listar roles', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'rol';
        return view('roles/index', $data);
    }

    public function listar()
    {
        $data = $this->roles->where('estado', '1')->findAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function new()
    {
        if (!verificar('nuevo rol', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['permisos'] = $this->permisos->findAll();
        $data['active'] = 'rol';
        return view('roles/nuevo', $data);
    }

    public function create()
    {
        if ($this->request->is('post') && verificar('nuevo rol', $this->session->permisos)) {
            $permisosSel = $this->request->getVar('permisos');
            $json = (empty($permisosSel)) ? null : json_encode($permisosSel);
            $data = [
                'id_rol' => $this->request->getVar('id_rol'),
                'nombre' => $this->request->getVar('nombre'),
                'permisos' => $json
            ];
            if ($this->roles->insert($data) === false) {
                $data['errors'] = $this->roles->errors();
                $data['permisos'] = $this->permisos->findAll();
                $data['active'] = 'rol';
                return view('roles/nuevo', $data);
            }
            return redirect()->to(base_url('roles'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'ROL REGISTRADO',
            ]);
        }else{
            return view('permisos'); 
        }
    }

    public function edit($id)
    {
        if (!verificar('editar rol', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['permisos'] = $this->permisos->findAll();
        $data['rol'] = $this->roles->find($id);
        $permisos = [];
        if ($data['rol']['permisos'] != null) {
            $permisos = json_decode($data['rol']['permisos'], true);
        }
        $data['activos'] = $permisos;
        $data['active'] = 'rol';
        return view('roles/editar', $data);
    }

    public function update($id)
    {
        if ($this->request->is('put') && verificar('editar rol', $this->session->permisos)) {
            $permisosSel = $this->request->getVar('permisos');
            $json = (empty($permisosSel)) ? null : json_encode($permisosSel);
            $data = [
                'id_rol' => $this->request->getVar('id_rol'),
                'nombre' => $this->request->getVar('nombre'),
                'permisos' => $json
            ];
            if ($this->roles->update($id, $data) === false) {
                $data['errors'] = $this->roles->errors();
                $data['permisos'] = $this->permisos->findAll();
                $data['rol'] = $this->roles->find($id);
                $permisos = [];
                if ($data['rol']['permisos'] != null) {
                    $permisos = json_decode($data['rol']['permisos'], true);
                }
                $data['activos'] = $permisos;
                $data['active'] = 'rol';
                return view('roles/editar', $data);
            }
            return redirect()->to(base_url('roles'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'ROL MODIFICADO',
            ]);
        }else{
            return view('permisos'); 
        }
    }

    public function delete($id)
    {
        if ($this->request->is('delete') && verificar('eliminar rol', $this->session->permisos)) {
            $data = $this->roles->update($id, ['estado' => 0]);
            if ($data) {
                return redirect()->to(base_url('roles'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'ROL DADO DE BAJA',
                ]);
            } else {
                return redirect()->to(base_url('roles'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        }else{
            return view('permisos'); 
        }
    }
}
