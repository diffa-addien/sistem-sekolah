<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPromotionStatusToEnrollments extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('enrollments', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Lulus', 'Naik Kelas', 'Tinggal Kelas', 'Pindah', 'Keluar'],
                'default'    => 'Aktif',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('enrollments', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Lulus', 'Mengulang', 'Pindah', 'Keluar'],
                'default'    => 'Aktif',
            ],
        ]);
    }
}