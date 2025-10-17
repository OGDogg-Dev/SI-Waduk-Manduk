<?php

namespace Database\Factories;

use App\Models\OperatingHour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk membuat data OperatingHour.
 *
 * @extends Factory<OperatingHour>
 */
class OperatingHourFactory extends Factory
{
    protected $model = OperatingHour::class;

    /**
     * Definisi nilai standar model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $openHour = $this->faker->numberBetween(6, 10);
        $closeHour = $this->faker->numberBetween($openHour + 7, 22);

        return [
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'open_time' => sprintf('%02d:00:00', $openHour),
            'close_time' => sprintf('%02d:00:00', $closeHour),
            'is_closed' => false,
            'attraction_id' => null,
        ];
    }
}
