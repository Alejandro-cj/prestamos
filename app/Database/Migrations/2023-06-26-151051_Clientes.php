<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Clientes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'identidad' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',                
            ],
            'num_identidad' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true
            ],
            'whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true
            ],
            'direccion' => [
                'type' => 'TEXT'
            ],
            'estado' => [
                'type' => 'INT',
                'constraint' => '11',
                'default'    => '1',
            ],
            'created_at' => [
                'type'       => 'DATETIME'
            ],
            'updated_at' => [
                'type'       => 'DATETIME'
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('clientes');
    }

    public function down()
    {
        $this->forge->dropTable('clientes');
    }
}
