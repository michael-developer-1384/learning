<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LearningPath;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LearningPath>
 */
class LearningPathFactory extends Factory
{
    protected $model = LearningPath::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'valid_from' => $this->faker->date(),
            'valid_until' => $this->faker->date(),
            'is_active' => $this->faker->boolean,
            'is_mandatory' => $this->faker->boolean,
            'tenant_id' => 1, // Default value, you can override this when using the factory
            'created_by_user' => null, // Default value, you can override this when using the factory
        ];
    }
}
