<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(3),
            'first_mobile' => $this->faker->phoneNumber(),
            'business_name' => $this->faker->company(),
            'second_mobile' => $this->faker->optional()->phoneNumber(),
            'email' => $this->faker->optional()->safeEmail(),
            'address' => $this->faker->optional()->streetAddress(),
            'whatsapp_mobile' => '233542092800',
            'region_id' => Region::query()->inRandomOrder()->first()->id,
            'town' => $this->faker->city(),
            'category_id' => ServiceCategory::query()->inRandomOrder()->first()->id,
            'gps' => $this->faker->optional()->latitude() . ',' . $this->faker->optional()->longitude(),
        ];
    }
}
