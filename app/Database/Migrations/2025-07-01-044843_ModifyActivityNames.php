<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyActivityNames extends Migration
{
    public function up()
    {
        // 1. Tambah kolom baru
        $this->forge->addColumn('activity_names', [
            'start_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'type'
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'start_time'
            ],
        ]);

        // 2. Ubah kolom 'type' untuk menambah opsi baru
        $this->forge->modifyColumn('activity_names', [
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['Sekolah', 'Rumah', 'Masuk', 'Pulang'],
                'default'    => 'Sekolah',
            ],
        ]);
    }

    public function down()
    {
        // Urutan dibalik saat rollback
        $this->forge->dropColumn('activity_names', ['start_time', 'end_time']);

        $this->forge->modifyColumn('activity_names', [
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['Sekolah', 'Rumah'],
                'default'    => 'Sekolah',
            ],
        ]);
    }
}