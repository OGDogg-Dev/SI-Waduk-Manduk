<?php

namespace Database\Factories;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory Merchant untuk mitra UMKM.
 *
 * @extends Factory<Merchant>
 */
class MerchantFactory extends Factory
{
    protected $model = Merchant::class;

    /**
     * Definisi default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'UMKM '.$this->faker->unique()->company(),
            'slug' => null,
            'category' => $this->faker->randomElement(['KULINER', 'SOUVENIR', 'SEWA_PERALATAN', 'LAINNYA']),
            'phone' => $this->faker->optional()->phoneNumber(),
            'whatsapp' => $this->faker->optional()->e164PhoneNumber(),
            'location' => $this->faker->address(),
            'is_verified' => $this->faker->boolean(60),
        ];
    }
}
