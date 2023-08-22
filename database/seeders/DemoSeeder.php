<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Company;
use App\Models\Department;
use App\Models\Position;

use App\Models\Role;
use App\Models\Permission;

use App\Models\LearningType;
use App\Models\ContentType;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Erstelle 5 Tenants
        $tenants = Tenant::factory(5)->create();

        foreach ($tenants as $tenant) {
            // Erstelle zwischen 1 und 10 Companies pro Tenant
            $companies = Company::factory(rand(1, 10))->create(['tenant_id' => $tenant->id]);

            // Erstelle einen Admin-User für jeden Tenant
            $admin = User::factory()->create([
                'tenant_id' => $tenant->id,
            ]);
            $admin->assignRole('Administrator');

            // Erstelle zwischen 5 und 20 Users pro Tenant
            $users = User::factory(rand(5, 20))->create(['tenant_id' => $tenant->id]);

            // Zwei User erhalten ein oder zwei der anderen Rollen (außer Super Admin, Administrator und Student)
            $roles = ['Editor', 'Author', 'Guest'];
            $selectedUsers = $users->random(2);
            foreach ($selectedUsers as $selectedUser) {
                $randomRoles = array_rand($roles, rand(1, 2));
                foreach ($randomRoles as $roleIndex) {
                    $selectedUser->assignRole($roles[$roleIndex]);
                }
            }

            // Die anderen User erhalten alle die Rolle Student
            $remainingUsers = $users->diff($selectedUsers);
            foreach ($remainingUsers as $remainingUser) {
                $remainingUser->assignRole('Student');
            }
        }
    }
}
