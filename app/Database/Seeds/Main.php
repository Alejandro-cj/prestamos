<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Main extends Seeder
{
    public function run()
    {
        $this->call('PermisosSeeder');
        $this->call('AdminSeeder');
        $this->call('RolesSeeder');
        $this->call('UsuariosSeeder');
    }
}
