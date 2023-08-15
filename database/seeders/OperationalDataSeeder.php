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
        $permissions = [
            ['name' => 'view_courses', 'description' => 'View all courses', 'category' => 'course'],
            ['name' => 'create_courses', 'description' => 'Create new courses', 'category' => 'course'],
            ['name' => 'edit_courses', 'description' => 'Edit existing courses', 'category' => 'course'],
            ['name' => 'delete_courses', 'description' => 'Delete courses', 'category' => 'course'],

            ['name' => 'view_users', 'description' => 'View all users', 'category' => 'user'],
            ['name' => 'create_users', 'description' => 'Create new users', 'category' => 'user'],
            ['name' => 'edit_users', 'description' => 'Edit existing users', 'category' => 'user'],
            ['name' => 'delete_users', 'description' => 'Delete users', 'category' => 'user'],

            ['name' => 'view_reports', 'description' => 'View all reports', 'category' => 'report'],
            ['name' => 'generate_reports', 'description' => 'Generate new reports', 'category' => 'report'],

            ['name' => 'view_billing', 'description' => 'View billing details', 'category' => 'billing'],
            ['name' => 'edit_billing', 'description' => 'Edit billing details', 'category' => 'billing'],

            ['name' => 'view_settings', 'description' => 'View system settings', 'category' => 'settings'],
            ['name' => 'edit_settings', 'description' => 'Edit system settings', 'category' => 'settings'],

            ['name' => 'view_content', 'description' => 'View all content', 'category' => 'content'],
            ['name' => 'create_content', 'description' => 'Create new content', 'category' => 'content'],
            ['name' => 'edit_content', 'description' => 'Edit existing content', 'category' => 'content'],
            ['name' => 'delete_content', 'description' => 'Delete content', 'category' => 'content'],

            ['name' => 'view_analytics', 'description' => 'View analytics data', 'category' => 'analytics'],
            ['name' => 'generate_analytics', 'description' => 'Generate analytics reports', 'category' => 'analytics'],

            ['name' => 'send_emails', 'description' => 'Send emails to users', 'category' => 'communication'],
            ['name' => 'view_communications', 'description' => 'View all communications', 'category' => 'communication'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }


        foreach (Role::ROLE_NAMES as $role) {
            Role::create(['name' => $role]);
        }

        foreach (LearningType::LEARNING_TYPES as $type => $isActive) {
            LearningType::create([
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
        }
    }
}
