<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    protected static function booted()
    {
        static::created(function ($tenant) {
            foreach (Department::DEPARTMENT_NAMES as $name) {
                Department::create([
                    'name' => $name,
                    'tenant_id' => $tenant->id
                ]);
            }
        });
    }
}
