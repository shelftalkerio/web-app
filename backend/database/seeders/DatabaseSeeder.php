<?php

namespace Database\Seeders;

use App\Enums\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Dipo George',
            'email' => 'dipo@shelftalker.io',
            'role' => UserRole::SuperAdmin->value,
            'approved' => 1,
        ]);
        User::factory()->create([
            'name' => 'Jak Clark',
            'email' => 'jak@shelftalker.io',
            'role' => UserRole::SuperAdmin->value,
            'approved' => 1,
        ]);
        $this->call(CompanySeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ModuleSeeder::class);
    }
}
