<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = Str::title($this->faker->unique()->words(3, true));

        return [
            'name' => $name,
            'sku' => Str::slug($name),
            'price' => fake()->randomFloat(2, 0, 999999.99),
            'stock' => fake()->numberBetween(1, 100),
            'synced_at' => fake()->dateTime(),
            'description' => fake()->text(),
            'store_id' => Store::factory(),
        ];
    }
}
