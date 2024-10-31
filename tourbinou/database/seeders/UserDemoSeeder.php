<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Ranium\SeedOnce\Traits\SeedOnce;

class UserDemoSeeder extends Seeder
{
    use SeedOnce;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@turbinou.com',
        ]);
    }
}
