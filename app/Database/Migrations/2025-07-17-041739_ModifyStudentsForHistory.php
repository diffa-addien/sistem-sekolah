<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyStudentsForHistory extends Migration
{
    public function up()
    {
        // Hapus kolom class_id dan status dari tabel students
        $this->forge->dropForeignKey('students', 'students_class_id_foreign');
        $this->forge->dropColumn('students', ['class_id', 'status']);
    }

    public function down()
    {
        // Logika untuk mengembalikan kolom jika di-rollback
        $this->forge->addColumn('students', [
            'class_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['Aktif', 'Lulus', 'Pindah', 'Keluar'], 'default' => 'Aktif'],
        ]);
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->processIndexes('students');
    }
}