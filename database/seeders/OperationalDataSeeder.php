<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\LearningType;
use App\Models\ContentType;

use Illuminate\Database\Seeder;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


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
/* 
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


 */




        // Erstellen Sie einen Admin-Benutzer
        $admin = User::factory()->create([
            'tenant_id' => null,
            'name' => 'admin',
            'email' => 'admin@test.com',
        ]); 

        $tenant = Tenant::factory()->create([
            'name' => 'SHD',
        ]);

        $admin->tenant_id = $tenant->id;
        $admin->save();
        
        $role = Role::create(['name' => 'Super Admin', 'tenant_id' => 1, 'created_by' => 1]);
        $permissions = Permission::all()->pluck('name');
        $role->givePermissionTo($permissions);

        $admin->assignRole('Super Admin');
/* 
        // Create Roles
        $role1 = Role::create(['name' => 'System-Administrator', 'tenant_id' => 1, 'created_by' => 1]);
        $role2 = Role::create(['name' => 'Administrator', 'tenant_id' => 1, 'created_by' => 1]);
        $role3 = Role::create(['name' => 'Editor', 'tenant_id' => 1, 'created_by' => 1]);
        $role4 = Role::create(['name' => 'Author', 'tenant_id' => 1, 'created_by' => 1]);
        $role5 = Role::create(['name' => 'Student', 'tenant_id' => 1, 'created_by' => 1]);
        $role6 = Role::create(['name' => 'Guest', 'tenant_id' => 1, 'created_by' => 1]);
 */
        /* $permission->assignRole($role1);
        $admin->assignRole('Administrator'); */



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
