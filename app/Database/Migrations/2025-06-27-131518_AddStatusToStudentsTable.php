<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToStudentsTable extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Lulus', 'Pindah', 'Keluar'],
                'default'    => 'Aktif',
                'after'      => 'photo' // Menempatkan kolom setelah kolom 'photo'
            ],
        ];
        $this->forge->addColumn('students', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('students', 'status');
    }
}