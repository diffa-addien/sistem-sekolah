<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'class_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'academic_year_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['Aktif', 'Lulus', 'Tinggal Kelas', 'Pindah'], 'default' => 'Aktif'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('academic_year_id', 'academic_years', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        $this->forge->dropTable('enrollments');
    }
}