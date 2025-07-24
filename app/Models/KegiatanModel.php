<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
    protected $table = 'activities';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id', 'activity_name_id', 'activity_date', 'description'];
    protected $useTimestamps = true;
}