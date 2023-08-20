<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\LearningType;
use App\Models\ContentType;

use Illuminate\Database\Seeder;

class OperationalDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    { 


        /* foreach (Permission::PERMISSIONS as $permission) {
            Permission::create($permission);
        }

        foreach (Role::ROLE_NAMES as $role) {
            Role::create(['name' => $role]);
        } */

        foreach (LearningType::LEARNING_TYPES as $type => $isActive) {
            LearningType::create([
                'name' => $type,
                'is_active' => $isActive
            ]);
        }

        foreach (ContentType::CONTENT_TYPES as $type => $isActive) {
            ContentType::create([
                'name' => $type,
                'is_active' => $isActive
            ]);
        }









        Tenant::factory()->create([
            'name' => 'SHD',
        ]);

        // Erstellen Sie einen Admin-Benutzer
        $admin = User::factory()->create([
            'tenant_id' => 1,
            'name' => 'admin',
            'email' => 'admin@test.com',
        ]);

/* 
        // Weisen Sie dem Admin-Benutzer die Rolle "System-Administrator" zu
        $systemAdminRole = Role::where('name', 'System-Administrator')->firstOrFail();
        $admin->roles()->attach($systemAdminRole->id);

        // Weisen Sie der Rolle "System-Administrator" alle Berechtigungen zu
        $allPermissions = Permission::all();
        $systemAdminRole->permissions()->sync($allPermissions->pluck('id'));

        // Jeder anderen Rolle zufällige Berechtigungen zuweisen
        $roles = Role::where('name', '<>', 'System-Administrator')->get();
        $permissions = Permission::all();

        foreach ($roles as $role) {
            $randomPermissions = $permissions->mapWithKeys(function ($permission) {
                return [$permission->id => ['is_active' => (bool)random_int(0, 1)]];
            });
            $role->permissions()->sync($randomPermissions);
        } */
    }
}
