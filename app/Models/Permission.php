<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'category', 'is_active'];

    const CATEGORIES = [
        'Course',
        'User',
        'Report',
        'Billing',
        'Settings',
        'Content',
        'Analytics',
        'Communication',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('is_active');
    }
}
