<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'identidad' => '123456789',
            'nombre'    => 'EMPRESA DE ALEJO',
            'telefono'    => '900897537',
            'correo'    => 'alejandrocj2002@gmail.com',
            'direccion'    => 'colombia',
            'mensaje'    => 'PAGAME',
            'tasa_interes'    => '19',
            'cuotas'    => '18',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Using Query Builder
        $this->db->table('configuracion')->insert($data);
    }
}
