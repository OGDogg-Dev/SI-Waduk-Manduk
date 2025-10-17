<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory Inquiry untuk pesan pengunjung.
 *
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->optional()->safeEmail(),
            'phone' => $this->faker->optional()->e164PhoneNumber(),
            'type' => $this->faker->randomElement(['PERTANYAAN', 'SARAN', 'PENGADUAN']),
            'message' => $this->faker->paragraph(),
            'status' => 'BARU',
            'handled_by' => null,
        ];
    }
}
