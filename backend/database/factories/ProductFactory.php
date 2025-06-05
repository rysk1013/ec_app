<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->realText(),
            'price' => fake()->numberBetween(10, 10000),
            'stock' => fake()->numberBetween(1, 100),
            'image_url' => fake()->imageUrl(),
            'category_id' => fake()->numberBetween(1, 9),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
