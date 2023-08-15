<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'valid_from' => $this->faker->date(),
            'valid_until' => $this->faker->date(),
            'is_active' => $this->faker->boolean,
            'is_mandatory' => $this->faker->boolean,
        ];
    }
}
