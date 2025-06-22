<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivityNameSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Upacara Bendera',
                'type' => 'Sekolah',
            ],
            [
                'name' => 'Pramuka',
                'type' => 'Sekolah',
            ],
            [
                'name' => 'Lomba Cerdas Cermat',
                'type' => 'Sekolah',
            ],
            [
                'name' => 'Membantu Orang Tua',
                'type' => 'Rumah',
            ],
            [
                'name' => 'Mengerjakan PR',
                'type' => 'Rumah',
            ],
        ];

        // Using Query Builder to insert multiple rows
        $this->db->table('activity_names')->insertBatch($data);
    }
}