<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tenant::factory()->create([
            'name' => 'SHD',
        ]);

        Role::factory(4)->create();

        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'role_id' => 1,
        ]);

        Company::factory(20)->create()->each(function ($company) {
            // Erstellen Sie einen Benutzer für die Firma mit der Rolle "2"
            User::factory()->create([
                'company_id' => $company->id,
                'role_id' => 2, 
                'tenant_id' => 1, 
            ]);

            // Erstellen Sie zwischen 5 und 100 Benutzer für die Firma mit der Rolle "4"
            $usersCount = rand(5, 100);
            User::factory($usersCount)->create([
                'company_id' => $company->id,
                'role_id' => 4, 
                'tenant_id' => 1, 
            ]);
        });
    }
}
