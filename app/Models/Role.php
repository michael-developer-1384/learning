<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{ 
    use HasFactory;

    protected $fillable = ['name', 'category', 'description'];

    const ROLE_NAMES = [
        'System-Administrator',
        'Administrator',
        'Editor',
        'Author',
        'Student',
        'Guest',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withPivot('is_active');
    }
    
}
