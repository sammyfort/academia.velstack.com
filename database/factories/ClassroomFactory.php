<?php

namespace Database\Factories;

use App\Enums\ClassGroup;
use App\Enums\ClassLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'name' => 'JHS ONE',
            'level' => ClassLevel::JUNIOR_HIGH->value,
            'group' => ClassGroup::JHS_ONE->value,
            'slug' => $this->faker->unique()->slug(),
        ];
    }
}
