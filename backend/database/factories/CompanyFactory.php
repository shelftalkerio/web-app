<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\User;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        return [
            'name' => fake()->name(),
            'user_id' => $user->id,
            'email' => $user->email,
            'website' => fake()->url(),
        ];
    }
}
