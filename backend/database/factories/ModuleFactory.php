<?php

namespace Database\Factories;

use App\Enums\ModuleStatus;
use App\Enums\ModuleType;
use App\Models\Module;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
            'name' => fake()->word(),
            'description' => fake()->text(120),
            'type' => Arr::random(ModuleType::cases())->value,
            'vendor' => fake()->word(),
            'config' => '{}',
            'active' => fake()->boolean(),
            'status' => Arr::random(ModuleStatus::cases())->value,
            'last_synced_at' => fake()->dateTime(),
            'store_id' => Store::factory(),
        ];
    }
}
