<?php

namespace Database\Factories;

use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory TicketType untuk data contoh tiket.
 *
 * @extends Factory<TicketType>
 */
class TicketTypeFactory extends Factory
{
    protected $model = TicketType::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $base = $this->faker->numberBetween(5000, 50000);

        return [
            'name' => 'Tiket '.$this->faker->unique()->word(),
            'slug' => null,
            'weekday_price' => $base,
            'weekend_price' => $base + 2000,
            'holiday_price' => $base + 4000,
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
