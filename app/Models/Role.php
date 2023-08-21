<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{ 
    use HasFactory;

    protected $fillable = ['name', 'category', 'description'];

    const PREDEFINED_ROLES = [
        'Administrator' => [
            'view_courses',
            'create_courses',
            'edit_courses',
            'delete_courses',
            'enroll_students',
            'view_enrollments',
            'view_assignments',
            'grade_assignments',
            'manage_users',
            'view_reports',
        ],
        'Editor' => [
            'view_courses',
            'edit_courses',
            'view_assignments',
            'grade_assignments',
        ],
        'Author' => [
            'create_courses',
            'edit_courses',
            'view_assignments',
        ],
        'Student' => [
            'view_courses',
            'view_assignments',
        ],
        'Guest' => [
            'view_courses',
        ],
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }
}
