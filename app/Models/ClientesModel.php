<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientesModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['identidad', 'num_identidad', 'nombre', 'apellido', 'telefono', 'whatsapp', 'correo', 'direccion', 'estado'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_cliente' => 'is_natural',
        'identidad' => 'required|min_length[2]',
        'num_identidad'    => [
            'rules'  => 'required|min_length[8]|is_unique[clientes.num_identidad,id,{id_cliente}]',
            'errors' => [
                'required' => 'El N° de identidad es obligatorio',
                'min_length' => 'El N° de identidad debe contener 8 caracteres',
                'is_unique' => 'El N° de identidad debe único',
            ],
        ],
        'nombre' => 'required|min_length[3]',
        'apellido' => 'required|min_length[3]',
        'telefono' => 'required|min_length[9]|is_unique[clientes.telefono,id,{id_cliente}]',
        'whatsapp' => 'required|min_length[9]|is_unique[clientes.whatsapp,id,{id_cliente}]',
        'correo' => 'required|valid_email|is_unique[clientes.correo,id,{id_cliente}]',
        'direccion' => 'required|min_length[4]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
