<?php

namespace App\Database\Seeds;

use App\Models\PermisosModel;
use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $permisos = new PermisosModel();
        $results = $permisos->findAll();
        $json = [];
        foreach ($results as $result) {
            $array = json_decode($result['campos'], true);
            for ($i=0; $i < count($array); $i++) { 
                array_push($json, $array[$i]);
            }
        }
        $data = [
            'nombre'    => 'ADMINISTRADOR',
            'permisos'    => json_encode($json),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        // Using Query Builder
        $this->db->table('roles')->insert($data);
    }
}
