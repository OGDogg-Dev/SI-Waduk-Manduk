<?php

namespace Database\Factories;

use App\Models\Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * Factory Closure untuk penutupan lokasi.
 *
 * @extends Factory<Closure>
 */
class ClosureFactory extends Factory
{
    protected $model = Closure::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::now()->addDays($this->faker->numberBetween(0, 30))->setTime(7, 0);

        return [
            'reason' => $this->faker->sentence(),
            'start_at' => $start,
            'end_at' => (clone $start)->addDays(1),
            'attraction_id' => null,
        ];
    }
}
