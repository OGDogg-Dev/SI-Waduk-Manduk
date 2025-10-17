<?php

namespace Database\Factories;

use App\Models\Attraction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk menghasilkan data dummy Attraction.
 *
 * @extends Factory<Attraction>
 */
class AttractionFactory extends Factory
{
    protected $model = Attraction::class;

    /**
     * Definisi nilai standar model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'slug' => null,
            'type' => $this->faker->randomElement(['WAHANA', 'SPOT', 'GENERAL']),
            'description' => $this->faker->paragraph(),
            'base_price' => $this->faker->optional()->randomFloat(2, 5000, 100000),
            'is_active' => $this->faker->boolean(85),
            'latitude' => $this->faker->latitude(-7.6, -7.3),
            'longitude' => $this->faker->longitude(112.2, 112.8),
        ];
    }
}
