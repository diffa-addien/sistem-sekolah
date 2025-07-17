<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContextToActivities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities', [
            'class_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'after' => 'student_id'],
            'academic_year_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'after' => 'class_id'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activities', ['class_id', 'academic_year_id']);
    }
}