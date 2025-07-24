<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SimplifyTransactions extends Migration
{
    public function up()
    {
        // Hapus foreign key dan kolom dari tabel attendances
        $this->forge->dropForeignKey('attendances', 'attendances_class_id_foreign');
        $this->forge->dropForeignKey('attendances', 'attendances_academic_year_id_foreign');
        $this->forge->dropColumn('attendances', ['class_id', 'academic_year_id']);

        // Hapus foreign key dan kolom dari tabel activities
        $this->forge->dropForeignKey('activities', 'activities_class_id_foreign');
        $this->forge->dropForeignKey('activities', 'activities_academic_year_id_foreign');
        $this->forge->dropColumn('activities', ['class_id', 'academic_year_id']);
    }

    public function down()
    {
        // Logika untuk mengembalikan jika di-rollback
        $this->forge->addColumn('attendances', [
            'class_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'academic_year_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);
        
        $this->forge->addColumn('activities', [
            'class_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'academic_year_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);
    }
}