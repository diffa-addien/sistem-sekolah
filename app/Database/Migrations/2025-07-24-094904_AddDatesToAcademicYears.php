<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDatesToAcademicYears extends Migration
{
    public function up()
    {
        $this->forge->addColumn('academic_years', [
            'start_date' => ['type' => 'DATE', 'null' => true, 'after' => 'status'],
            'end_date'   => ['type' => 'DATE', 'null' => true, 'after' => 'start_date'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('academic_years', ['start_date', 'end_date']);
    }
}