<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Prestamos extends Migration
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
            'importe' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'modalidad' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'tasa_interes' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'cuotas' => [
                'type'       => 'INT',
                'constraint' => '11'
            ],
            'fecha' => [
                'type' => 'DATETIME'
            ],
            'fecha_venc' => [
                'type' => 'DATE',
                'null' => true
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
            ],
            'id_cliente' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_usuario' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_cliente', 'clientes', 'id', 'CASCADE', 'CASCADE', 'fk_clientes');
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id', 'CASCADE', 'CASCADE', 'fk_usuarios');
        $this->forge->createTable('prestamos');
    }

    public function down()
    {
        $this->forge->dropTable('prestamos');
    }
}
