<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\_Parent;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\Term;
use Database\Factories\ClassroomFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       

       DB::transaction(function () {
            $school = School::factory()->create();
            Model::withoutEvents(function () use ($school) {
                $staff = Staff::factory()->create([
                    'school_id' => $school->id,
                ]);

                $staff->bank()->create([
                    'uuid' => Str::uuid(),
                    'school_id' => $school->id,
                    'name' => 'Fidelity Bank',
                    'branch' => 'Sunyani Main',
                    'account_number' => '10023234355443'
                ]);
                Classroom::factory()->create(['school_id' => $school->id]);
                Subject::factory()->create(['school_id' => $school->id]);
                Term::factory()->create(['school_id' => $school->id]);

                $this->call(PermissionSeeder::class);

                $allPermissions = Permission::all();
                $staff->givePermissionTo($allPermissions);
            });
        });
    }
}
