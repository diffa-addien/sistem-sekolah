<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContextToAttendances extends Migration
{
    public function up()
    {
        $this->forge->addColumn('attendances', [
            'class_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'after' => 'student_id'],
            'academic_year_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'after' => 'class_id'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('attendances', ['class_id', 'academic_year_id']);
    }
}