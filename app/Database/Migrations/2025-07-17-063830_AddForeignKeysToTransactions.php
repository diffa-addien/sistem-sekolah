<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeysToTransactions extends Migration
{
    public function up()
    {
        // Menambahkan Foreign Key untuk tabel 'attendances'
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('academic_year_id', 'academic_years', 'id', 'SET NULL', 'CASCADE');
        $this->forge->processIndexes('attendances');

        // Menambahkan Foreign Key untuk tabel 'activities'
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('academic_year_id', 'academic_years', 'id', 'SET NULL', 'CASCADE');
        $this->forge->processIndexes('activities');
    }

    public function down()
    {
        // Rollback untuk tabel 'attendances'
        $this->forge->dropForeignKey('attendances', 'attendances_class_id_foreign');
        $this->forge->dropForeignKey('attendances', 'attendances_academic_year_id_foreign');
        
        // Rollback untuk tabel 'activities'
        $this->forge->dropForeignKey('activities', 'activities_class_id_foreign');
        $this->forge->dropForeignKey('activities', 'activities_academic_year_id_foreign');
    }
}