<?php

namespace App\Models;

use CodeIgniter\Model;

class DetPrestamoModel extends Model
{
    protected $table            = 'detalle_prestamos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['cuota', 'fecha_venc', 'importe_cuota', 'estado', 'id_prestamo'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
