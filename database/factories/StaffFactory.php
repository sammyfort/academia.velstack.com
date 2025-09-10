<?php

namespace Database\Factories;

use App\Enums\MaritalStatus;
use App\Enums\Region;
use App\Enums\Religion;
use App\Enums\StaffExperience;
use App\Enums\StaffQualification;
use App\Enums\StaffType;
use App\Models\School;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    protected $model = Staff::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        return [
            'uuid' => $this->faker->uuid(),
            'school_id' => School::factory(),
            'title' => $this->faker->title(),
            'first_name' => 'Sammy',
            'last_name' => 'Fort',
            'licence_no' => $this->faker->numberBetween(1000, 99999),
            'staff_id' => $this->faker->unique()->randomNumber(),
            'password' => Hash::make('123'),
            'email' => 'thesamuelfort@gmail.com',
            'phone' => $this->faker->phoneNumber(),
            'gender' => 'Male',
            'dob' => now(),
            'religion' => Religion::cases()[0]->value,
            'city' => 'Tokyo',
            'bio' => null,
            'basic_salary' => 2000,
            'designation' => StaffType::TEACHING_STAFF->value,
            'national_id' => 'GHA-1224343434-3',
            'qualification' => StaffQualification::cases()[0]->value,
            'experience' => StaffExperience::cases()[0]->value,
            'region' => Region::cases()[0]->value,
            'marital_status' => MaritalStatus::cases()[0]->value,
        ];
    }
}
