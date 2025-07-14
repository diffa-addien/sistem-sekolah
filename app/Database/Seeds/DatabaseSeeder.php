<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('TahunAjaranSeeder');
        $this->call('UserSeeder');
        $this->call('KelasSeeder');
        $this->call('SiswaSeeder');
    }
}