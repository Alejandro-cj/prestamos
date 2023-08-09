<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Cajas extends Migration
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
            'monto_inicial' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'fecha_apertura' => [
                'type'       => 'DATETIME'
            ],
            'estado' => [
                'type' => 'INT',
                'constraint' => '11',
                'default'    => '1'
            ],
            'created_at' => [
                'type'       => 'DATETIME'
            ],
            'updated_at' => [
                'type'       => 'DATETIME'
            ],
            'id_usuario' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id', 'CASCADE', 'CASCADE', 'fk_usuario_caja');
        $this->forge->createTable('cajas');
    }

    public function down()
    {
        $this->forge->dropTable('cajas');
    }
}
