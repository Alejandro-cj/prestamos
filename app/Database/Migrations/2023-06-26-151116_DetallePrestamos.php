<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetallePrestamos extends Migration
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
            'cuota' => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'fecha_venc' => [
                'type'       => 'DATE'
            ],
            'importe_cuota' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2'
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
            'id_prestamo' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_prestamo', 'prestamos', 'id', 'CASCADE', 'CASCADE', 'fk_prestamos');
        $this->forge->createTable('detalle_prestamos');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_prestamos');
    }
}
