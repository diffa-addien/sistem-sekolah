<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterStudentsTableAddClassIdNull extends Migration
{
    public function up()
    {
        // Logika untuk mengubah kolom class_id menjadi NULLable
        $this->forge->modifyColumn('students', [
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Ini adalah perubahan yang Anda inginkan
            ],
        ]);
    }

    public function down()
    {
        // Logika untuk mengembalikan kolom class_id menjadi NOT NULL
        $this->forge->modifyColumn('students', [
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false, // Mengembalikan ke NOT NULL
            ],
        ]);

        // Opsional: Jika Anda menghapus dan menambahkan kembali FK di up(),
        // Anda mungkin perlu mengembalikan FK ke kondisi sebelumnya di down().
        /*
        $this->forge->dropForeignKey('students', 'students_class_id_foreign'); // Ganti 'students_class_id_foreign' dengan nama FK Anda
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE'); // Mengembalikan ke CASCADE jika itu sebelumnya
        */
    }
}