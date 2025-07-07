<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeacherIdToClasses extends Migration
{
    public function up()
    {
        $fields = [
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Boleh kosong jika belum ada wali kelas
                'after'      => 'name'
            ],
        ];
        $this->forge->addColumn('classes', $fields);

        // Tambahkan foreign key constraint
        $this->forge->addForeignKey('teacher_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->processIndexes('classes');
    }

    public function down()
    {
        $this->forge->dropForeignKey('classes', 'classes_teacher_id_foreign');
        $this->forge->dropColumn('classes', 'teacher_id');
    }
}