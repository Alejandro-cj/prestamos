<?php

namespace App\Models;

use CodeIgniter\Model;

class CajasModel extends Model
{
    protected $table            = 'cajas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['monto_inicial', 'fecha_apertura', 'ganancia', 'estado', 'id_usuario'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_caja' => 'is_natural',
        'monto_inicial'    => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'El monto es requerido'
            ],
        ],
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function calcularMovimientos($id_usuario) {
        $prestamos = new PrestamosModel();
        $inicial = $this->select('monto_inicial')->where([
            'estado' => '1',
            'id_usuario' => $id_usuario
        ])->first();

        $incialSaldo = (empty($inicial)) ? 0 : $inicial['monto_inicial'];

        $egreso = $prestamos->selectSum('importe')->where([
            'estado' => '1',
            'id_usuario' => $id_usuario
        ])->first();

        $ingresos = $prestamos
            ->select('d.importe_cuota, d.estado')
            ->join('detalle_prestamos AS d', 'prestamos.id = d.id_prestamo')
            ->where([
                'prestamos.id_usuario' => $id_usuario,
                'prestamos.estado' => '1'
            ])->findAll();
        $totalIngreso = 0;
        foreach ($ingresos as $ingreso) {
            if ($ingreso['estado'] == 0) {
                $totalIngreso += $ingreso['importe_cuota'];
            }
        }

        $data['inicial'] = $incialSaldo;
        $data['egreso'] = ($egreso['importe'] != null) ? $egreso['importe'] : '0';
        $data['ingreso'] = $totalIngreso;
        //CALCULAR SALDO
        $data['saldo'] = ($data['inicial'] - $data['egreso']) + $data['ingreso'];
        
        $data['decimales'] = [
            'inicial' => number_format($data['inicial'], 2),
            'egreso' => number_format($data['egreso'], 2),
            'ingreso' => number_format($data['ingreso'], 2),
            'saldo' => number_format($data['saldo'], 2)
        ];
        return $data;
    }
}
