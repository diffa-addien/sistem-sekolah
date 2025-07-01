<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityNameModel extends Model
{
    protected $table            = 'activity_names';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'type', 'start_time', 'end_time'];
    protected $useTimestamps    = true;
}