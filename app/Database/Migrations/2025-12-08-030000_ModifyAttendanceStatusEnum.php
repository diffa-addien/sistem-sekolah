<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyAttendanceStatusEnum extends Migration
{
    public function up()
    {
        // Modify the status column to include 'Alpa' and remove 'Alfa'
        // Note: This matches the 'status' column definition in CreateAttendancesTable but with 'Alpa'
        $this->forge->modifyColumn('attendances', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                'default'    => 'Hadir',
            ],
        ]);
    }

    public function down()
    {
        // Revert back to 'Alfa'
        $this->forge->modifyColumn('attendances', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                'default'    => 'Hadir',
            ],
        ]);
    }
}
