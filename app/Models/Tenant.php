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
                    'tenant_id' => $tenant->id,
                    'created_by' => auth()->id() ?? 1
                ]);
            }
            
            foreach (Position::POSITION_NAMES as $positionName) {
                Position::create([
                    'name' => $positionName,
                    'tenant_id' => $tenant->id,
                    'created_by' => auth()->id() ?? 1
                ]);
            }

            // Erstellen Sie dann die Rollen und weisen Sie ihnen Berechtigungen zu
            foreach (Role::PREDEFINED_ROLES as $roleName => $permissions) {
                $role = Role::create([
                    'name' => $roleName,
                    'tenant_id' => $tenant->id
                ]);
                $role->givePermissionTo($permissions);
            }

        });
    }
}
