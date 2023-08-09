<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientesModel;

class ClientesController extends BaseController
{
    private $clientes, $session;
    public function __construct()
    {
        $this->clientes = new ClientesModel();
        helper(['form']);
        $this->session = session();
    }
    public function index()
    {
        if (!verificar('listar clientes', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'cliente';
        return view('clientes/index', $data);
    }

    public function listar()
    {
        $data = $this->clientes->where('estado', '1')->findAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function new()
    {
        if (!verificar('nuevo cliente', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'cliente';
        return view('clientes/nuevo', $data);
    }

    public function create()
    {
        if ($this->request->is('post') && verificar('nuevo cliente', $this->session->permisos)){
            $data = [
                'id_cliente' => $this->request->getVar('id_cliente'),
                'identidad' => $this->request->getVar('identidad'),
                'num_identidad' => $this->request->getVar('num_identidad'),
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'telefono' => $this->request->getVar('telefono'),
                'whatsapp' => $this->request->getVar('whatsapp'),
                'correo' => $this->request->getVar('correo'),
                'direccion' => $this->request->getVar('direccion')
            ];
            if ($this->clientes->insert($data) === false) {
                $data['errors'] = $this->clientes->errors();
                $data['active'] = 'cliente';
                return view('clientes/nuevo', $data);
            }
            return redirect()->to(base_url('clientes'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'CLIENTE REGISTRADO',
            ]);
        }else{
            return view('permisos');
        }
              
    }

    public function edit($idCliente){
        if (!verificar('editar cliente', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['cliente'] = $this->clientes->where('id', $idCliente)->first();
        $data['active'] = 'cliente';
        return view('clientes/edit', $data);
    }

    public function update($idCliente)
    {
        if ($this->request->is('put') && verificar('editar cliente', $this->session->permisos)){
            $data = [
                'id_cliente' => $this->request->getVar('id_cliente'),
                'identidad' => $this->request->getVar('identidad'),
                'num_identidad' => $this->request->getVar('num_identidad'),
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'telefono' => $this->request->getVar('telefono'),
                'whatsapp' => $this->request->getVar('whatsapp'),
                'correo' => $this->request->getVar('correo'),
                'direccion' => $this->request->getVar('direccion')
            ];
            if ($this->clientes->update($idCliente, $data) === false) {
                $data['errors'] = $this->clientes->errors();
                $data['cliente'] = $this->clientes->where('id', $idCliente)->first();
                $data['active'] = 'cliente';
                return view('clientes/edit', $data);
            }
            return redirect()->to(base_url('clientes'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'CLIENTE MODIFICADO',
            ]); 
        }else{
            return view('permisos');
        }
             
    }

    public function delete($idCliente) {
        if ($this->request->is('delete') && verificar('eliminar cliente', $this->session->permisos)) {
            //$data = $this->usuarios->delete($idUsuario);
            $data = $this->clientes->update($idCliente, ['estado' => '0']);
            if ($data) {
                return redirect()->to(base_url('clientes'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'CLIENTE DADO DE BAJA',
                ]);
            } else {
                return redirect()->to(base_url('clientes'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            } 
            
        }else{
            return view('permisos');
        }
    }
}
