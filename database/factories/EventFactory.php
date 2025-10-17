<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * Factory Event untuk membuat data acara.
 *
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::now()->addDays($this->faker->numberBetween(1, 60))->setTime(8, 0);

        return [
            'title' => $this->faker->unique()->sentence(3),
            'slug' => null,
            'description' => $this->faker->paragraphs(3, true),
            'start_at' => $start,
            'end_at' => (clone $start)->addHours(4),
            'venue' => 'Area '.$this->faker->word(),
            'is_published' => $this->faker->boolean(60),
        ];
    }
}
