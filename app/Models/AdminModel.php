<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'configuracion';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['identidad', 'nombre', 'telefono', 'correo', 'direccion',
    'mensaje', 'tasa_interes', 'cuotas'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id' => 'is_natural_no_zero',
        'identidad' => 'required|min_length[8]',
        'nombre' => 'required|min_length[3]',
        'telefono' => 'required|min_length[9]|is_unique[configuracion.telefono,id,{id}]',
        'correo' => 'required|valid_email|is_unique[configuracion.correo,id,{id}]',
        'cuotas' => 'required',
        'direccion' => 'required|min_length[4]',
        'tasa_interes'    => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'Tasa de interes es obligatorio',
            ],
        ]
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
