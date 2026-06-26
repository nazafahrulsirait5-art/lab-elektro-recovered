<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModulPraktikum extends Migration
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
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_modul' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('modul_praktikum');
    }

    public function down()
    {
        $this->forge->dropTable('modul_praktikum');
    }
}
