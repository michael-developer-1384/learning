<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        static $roles = ['System-Administrator', 'Administrator', 'Moderator', 'Participant'];
        static $index = 0;

        return [
            'name' => $roles[$index++ % count($roles)],
        ];
    }
}
