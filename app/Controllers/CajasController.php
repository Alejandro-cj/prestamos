<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CajasModel;

class CajasController extends BaseController
{
    private $cajas, $session;
    public function __construct()
    {
        helper(['form']);
        $this->cajas = new CajasModel();
        $this->session = session();
    }
    public function index()
    {
        if (!verificar('ver saldo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['caja'] = $this->cajas->where([
            'estado' => '1',
            'id_usuario' => $this->session->id_usuario
        ])->first();
        $data['active'] = 'caja';
        return view('cajas/index', $data);
    }

    public function new()
    {
        if (!verificar('ver saldo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['active'] = 'caja';
        return view('cajas/nuevo', $data);
    }

    public function create()
    {
        if ($this->request->is('post') && verificar('ver saldo', $this->session->permisos)) {
            $data = [
                'id_caja' => $this->request->getVar('id_caja'),
                'monto_inicial' => $this->request->getVar('monto'),
                'fecha_apertura' => date('Y-m-d H:i:s'),
                'id_usuario' => $this->session->id_usuario
            ];
            $consulta = $this->cajas->where([
                'estado' => '1',
                'id_usuario' => $this->session->id_usuario
            ])->first();
            if (empty($consulta)) {
                if ($this->cajas->insert($data) === false) {
                    $data['errors'] = $this->cajas->errors();
                    $data['active'] = 'caja';
                    return view('cajas/nuevo', $data);
                }
                return redirect()->to(base_url('cajas'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'MONTO REGISTRADO',
                ]);
            } else {
                return redirect()->to(base_url('cajas'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'YA TIENES UN MONTO INICAL',
                ]);
            }
        } else {
            return view('permisos');
        }
    }

    public function edit($id)
    {
        if (!verificar('ver saldo', $this->session->permisos)) {
            return view('permisos');
            exit;
        }
        $data['caja'] = $this->cajas->where('id', $id)->first();
        $data['active'] = 'caja';
        return view('cajas/edit', $data);
    }

    public function update($id)
    {
        if ($this->request->is('put') && verificar('ver saldo', $this->session->permisos)) {
            $data = [
                'id_caja' => $this->request->getVar('id_caja'),
                'monto_inicial' => $this->request->getVar('monto')
            ];

            if ($this->cajas->update($id, $data) === false) {
                $data['errors'] = $this->cajas->errors();
                $data['caja'] = $this->cajas->where('id', $id)->first();
                $data['active'] = 'caja';
                return view('cajas/edit', $data);
            }
            return redirect()->to(base_url('cajas'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'MONTO MODIFICADO',
            ]);
        } else {
            return view('permisos');
        }
    }

    public function movimientos()
    {
        if (!verificar('ver saldo', $this->session->permisos)) {
            $data = [];
        }else{
            $data = $this->cajas->calcularMovimientos($this->session->id_usuario);
            echo json_encode($data);
        }        
        die();
    }
}
