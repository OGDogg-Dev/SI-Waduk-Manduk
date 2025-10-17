<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory Setting untuk konfigurasi aplikasi.
 *
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => 'config_'.$this->faker->unique()->word(),
            'value' => [
                'text' => $this->faker->sentence(),
            ],
        ];
    }
}
