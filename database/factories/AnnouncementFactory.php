<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * Factory Announcement untuk membuat pengumuman.
 *
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(4),
            'slug' => null,
            'content' => $this->faker->paragraphs(2, true),
            'severity' => $this->faker->randomElement(['INFO', 'WARNING', 'ALERT']),
            'published_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 3)),
            'expired_at' => null,
        ];
    }
}
