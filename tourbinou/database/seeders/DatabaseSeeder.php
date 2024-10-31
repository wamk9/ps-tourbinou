<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ranium\SeedOnce\Traits\SeedOnce;

class DatabaseSeeder extends Seeder
{
    use SeedOnce;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InitSeeder::class,
            UserDemoSeeder::class
        ]);
    }
}
