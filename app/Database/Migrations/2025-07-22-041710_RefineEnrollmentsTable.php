<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefineEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('enrollments', [
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Mengizinkan nilai NULL
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Lulus', 'Mengulang', 'Pindah', 'Keluar'], // Opsi status baru
                'default'    => 'Aktif',
            ],
        ]);
    }

    public function down()
    {
        // Logika untuk mengembalikan ke kondisi semula jika di-rollback
        $this->forge->modifyColumn('enrollments', [
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Lulus', 'Tinggal Kelas', 'Pindah'],
                'default'    => 'Aktif',
            ],
        ]);
    }
}