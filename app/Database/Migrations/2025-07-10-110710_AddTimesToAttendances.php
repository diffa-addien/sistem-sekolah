<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimesToAttendances extends Migration
{
    public function up()
    {
        $this->forge->addColumn('attendances', [
            'check_in_time' => ['type' => 'TIME', 'null' => true, 'after' => 'status'],
            'check_out_time' => ['type' => 'TIME', 'null' => true, 'after' => 'check_in_time'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('attendances', ['check_in_time', 'check_out_time']);
    }
}