<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {


        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Dipo George',
            'email' => 'dipo@shelftalker.io',
        ]);
        User::factory()->create([
            'name' => 'Jak Clark',
            'email' => 'jak@shelftalker.io',
        ]);
        $this->call(CompanySeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ModuleSeeder::class);
    }
}
