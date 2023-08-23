<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'category'];

    const PERMISSIONS = [
        ['name' => 'view_courses', 'description' => 'View all courses', 'category' => 'Courses'],
        ['name' => 'edit_courses', 'description' => 'Edit courses', 'category' => 'Courses'],
        ['name' => 'delete_courses', 'description' => 'Delete courses', 'category' => 'Courses'],
        ['name' => 'create_courses', 'description' => 'Create new courses', 'category' => 'Courses'],
        ['name' => 'view_students', 'description' => 'View all students', 'category' => 'Students'],
        ['name' => 'edit_students', 'description' => 'Edit students', 'category' => 'Students'],
        ['name' => 'delete_students', 'description' => 'Delete students', 'category' => 'Students'],
        ['name' => 'create_students', 'description' => 'Create new students', 'category' => 'Students'],
        ['name' => 'view_teachers', 'description' => 'View all teachers', 'category' => 'Teachers'],
        ['name' => 'edit_teachers', 'description' => 'Edit teachers', 'category' => 'Teachers'],
        ['name' => 'delete_teachers', 'description' => 'Delete teachers', 'category' => 'Teachers'],
        ['name' => 'create_teachers', 'description' => 'Create new teachers', 'category' => 'Teachers'],
        ['name' => 'view_exams', 'description' => 'View all exams', 'category' => 'Exams'],
        ['name' => 'manage_exams', 'description' => 'Manage exams', 'category' => 'Exams'],
    ];
}
