<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->firstName, // Middle name can be nullable
            'last_name' => $this->faker->lastName,
            'index_number' => $this->faker->unique()->numerify('STU-######-#####-##'),
            'school_id' => 1, // Link to a School
            'previous_school_id' => 1, // Nullable, link to a School
            'class_id' => 1, // Link to a Classroom
            'parent_id' => 1, // Link to a Parent
            'password' => bcrypt('password'), // Default hashed password
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->optional()->phoneNumber,
            'image' => $this->faker->optional()->imageUrl(200, 200, 'people'), // Random image URL
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'dob' => $this->faker->date('Y-m-d', '-10 years'), // Random DOB
            'religion' => $this->faker->optional()->randomElement(['Christianity', 'Islam', 'Hinduism', 'Other']),
            'region' => $this->faker->optional()->state,
            'city' => $this->faker->optional()->city,
            'bio' => 'bio', // Random biography
            'is_completed' => $this->faker->boolean(80), // 80% chance to be true
        ];
    }
    // App\Models\Student::factory()->count(50)->create();
}
