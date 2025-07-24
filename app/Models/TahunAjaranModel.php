<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    // Nama tabel di database
    protected $table            = 'academic_years';
    
    // Primary key dari tabel
    protected $primaryKey       = 'id';

    // Mengizinkan auto-increment
    protected $useAutoIncrement = true;

    // Tipe data yang akan dikembalikan, 'array' atau 'object'
    protected $returnType       = 'array';
    
    // Atur ke true jika Anda ingin soft deletes (data tidak benar-benar dihapus)
    protected $useSoftDeletes   = false;

    // Kolom yang diizinkan untuk diisi melalui form (mass assignment)
    // Ini PENTING untuk keamanan!
    protected $allowedFields    = ['year', 'status', 'start_date', 'end_date'];

    // Menggunakan fitur timestamps bawaan CodeIgniter
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}