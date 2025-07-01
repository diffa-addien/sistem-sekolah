<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Kolom yang diizinkan untuk diisi melalui form.
    // Ini mencakup semua data siswa, termasuk foreign key dan foto.
    protected $allowedFields    = [
        'class_id',
        'user_id',
        'nis',
        'card_uid',
        'full_name',
        'gender',
        'birth_date',
        'photo'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}