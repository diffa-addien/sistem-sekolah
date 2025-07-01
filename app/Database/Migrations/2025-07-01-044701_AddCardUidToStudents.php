<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCardUidToStudents extends Migration
{
    public function up()
    {
        $fields = [
            'card_uid' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
                'null'       => true, // Boleh kosong jika siswa belum punya kartu
                'after'      => 'nis'
            ],
        ];
        $this->forge->addColumn('students', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('students', 'card_uid');
    }
}