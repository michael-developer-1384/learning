<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    const PREDEFINED_ROLES = [
        'Admin' => ['permissions' => ['view_courses', 'edit_courses', 'delete_courses', 'create_courses', 'view_students', 'edit_students', 'delete_students', 'create_students', 'view_teachers', 'edit_teachers', 'delete_teachers', 'create_teachers', 'view_exams', 'manage_exams']],
        'Teacher' => ['permissions' => ['view_courses', 'edit_courses', 'view_students', 'view_teachers', 'view_exams', 'manage_exams']],
        'Student' => ['permissions' => ['view_courses', 'view_exams']],
        'Guest' => ['permissions' => ['view_courses']],
        'Manager' => ['permissions' => ['view_courses', 'edit_courses', 'view_students', 'edit_students', 'view_teachers', 'edit_teachers', 'view_exams']],
        'Editor' => ['permissions' => ['view_courses', 'edit_courses', 'view_exams']],
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class); 
    }

}
