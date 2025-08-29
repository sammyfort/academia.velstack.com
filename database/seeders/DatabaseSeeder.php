<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // import service categories into DB
        Artisan::call('import:sc');

        $this->call([
            CountrySeeder::class,
            SignboardCategorySeeder::class,
            PromotionPlanSeeder::class,
            JobCategorySeeder::class,
            ProductCategorySeeder::class,
            ServiceCategorySeeder::class,
            UserSeeder::class,
        ]);
    }
}
