<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Admin extends Migration
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
                'constraint' => '20',
                'unique'     => true
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'telefono' => [
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
            'mensaje' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tasa_interes' => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'cuotas' => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'created_at' => [
                'type'       => 'DATETIME'
            ],
            'updated_at' => [
                'type'       => 'DATETIME'
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('configuracion');
    }

    public function down()
    {
        $this->forge->dropTable('configuracion');
    }
}
