<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory Facility untuk fasilitas wisata.
 *
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Fasilitas '.$this->faker->unique()->word(),
            'slug' => null,
            'icon' => null,
            'description' => $this->faker->sentence(),
            'is_available' => $this->faker->boolean(90),
        ];
    }
}
