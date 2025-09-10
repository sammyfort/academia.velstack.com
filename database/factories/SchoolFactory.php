<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
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
            'name' => 'Sunyani Technical University',
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '0123456789',
            'phone2' => '0123456789',
            'region' => $this->faker->city,
            'district' => $this->faker->streetName(),
            'town' => $this->faker->city(),
            'postal_address' => $this->faker->postcode(),
            'gps' => $this->faker->latitude(),
            'favicon' => $this->faker->imageUrl(),
            'cover_image' => $this->faker->imageUrl(),
        ];
    }

}
