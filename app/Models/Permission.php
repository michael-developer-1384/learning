<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    protected $fillable = ['name'];

    const PERMISSIONS = [
        'view_courses' => ['description' => 'View courses', 'category' => 'Courses'],
        'create_courses' => ['description' => 'Create new courses', 'category' => 'Courses'],
        'edit_courses' => ['description' => 'Edit existing courses', 'category' => 'Courses'],
        'delete_courses' => ['description' => 'Delete courses', 'category' => 'Courses'],

        'enroll_students' => ['description' => 'Enroll students to courses', 'category' => 'Enrollments'],
        'view_enrollments' => ['description' => 'View course enrollments', 'category' => 'Enrollments'],

        'view_assignments' => ['description' => 'View assignments', 'category' => 'Assignments'],
        'grade_assignments' => ['description' => 'Grade student assignments', 'category' => 'Assignments'],

        'manage_users' => ['description' => 'Manage system users', 'category' => 'Users'],
        'view_reports' => ['description' => 'View LMS reports', 'category' => 'Reports'],
    ];
    
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

    
}
