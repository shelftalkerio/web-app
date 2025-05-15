<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\Store;

class ModuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Module::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'type' => fake()->word(),
            'vendor' => fake()->word(),
            'config' => '{}',
            'active' => fake()->boolean(),
            'status' => fake()->word(),
            'last_synced_at' => fake()->dateTime(),
            'store_id' => Store::factory(),
        ];
    }
}
