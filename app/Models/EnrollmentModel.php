<?php namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['student_id', 'class_id', 'academic_year_id', 'status'];
}