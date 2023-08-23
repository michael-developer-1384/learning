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

            foreach (Role::PREDEFINED_ROLES as $roleName => $roleData) {
                $role = Role::create([
                    'tenant_id' => $tenant->id,
                    'name' => $roleName,
                ]);
        
                $permissions = Permission::whereIn('name', $roleData['permissions'])->get();
        
                // Bereiten Sie die Daten für die Pivot-Tabelle vor, einschließlich der tenant_id
                $pivotData = [];
                foreach ($permissions as $permission) {
                    $pivotData[$permission->id] = ['tenant_id' => $tenant->id];
                }
        
                // Verwenden Sie sync() anstelle von attach(), um die Pivot-Daten zu übergeben
                $role->permissions()->sync($pivotData);
            }
        });
    }
}
