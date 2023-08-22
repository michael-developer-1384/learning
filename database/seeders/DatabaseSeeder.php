<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(OperationalDataSeeder::class);
        //$this->call(DemoSeeder::class);
        //$this->call(DemoDataSeeder::class);
    }
}
