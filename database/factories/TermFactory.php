<?php

namespace Database\Factories;

use App\Enums\TermStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Term>
 */
class TermFactory extends Factory
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
            'name' => '2024/25 TERM ONE',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'next_term_begins' => now()->addDays(60),
            'status' => TermStatus::ACTIVE->value,
            'days' => 65,
        ];
    }
}
