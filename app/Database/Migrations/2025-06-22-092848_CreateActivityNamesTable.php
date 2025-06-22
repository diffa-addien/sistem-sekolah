<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityNamesTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['Sekolah', 'Rumah'],
                'default'    => 'Sekolah',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('activity_names');
    }

    public function down()
    {
        $this->forge->dropTable('activity_names');
    }
}