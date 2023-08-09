<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usuarios extends Migration
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
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true
            ],
            'direccion' => [
                'type' => 'TEXT'
            ],
            'clave' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'perfil' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'estado' => [
                'type' => 'INT',
                'constraint' => '11',
                'default'    => '1',
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'verify' => [
                'type' => 'INT',
                'constraint' => '11',
                'default'    => '0',
            ],
            'created_at' => [
                'type'       => 'DATETIME'
            ],
            'updated_at' => [
                'type'       => 'DATETIME'
            ],
            'id_rol' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_rol', 'roles', 'id', 'CASCADE', 'CASCADE', 'fk_roles');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
