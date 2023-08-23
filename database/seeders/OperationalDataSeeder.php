<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\LearningType;
use App\Models\ContentType;

use Illuminate\Database\Seeder;


use App\Models\Role;
use App\Models\Permission;


class OperationalDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    { 
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
        
    }
}
