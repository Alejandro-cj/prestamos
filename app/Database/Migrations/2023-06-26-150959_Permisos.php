<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Permisos extends Migration
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
            'modulo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'campos' => [
                'type' => 'TEXT'
            ],
            'created_at' => [
                'type'       => 'DATETIME'
            ],
            'updated_at' => [
                'type'       => 'DATETIME'
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('permisos');
    }

    public function down()
    {
        $this->forge->dropTable('permisos');
    }
}
