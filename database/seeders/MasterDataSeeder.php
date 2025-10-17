<?php

namespace Database\Seeders;

use App\Models\Attraction;
use App\Models\Facility;
use App\Models\Merchant;
use App\Models\OperatingHour;
use App\Models\TicketType;
use Illuminate\Database\Seeder;

/**
 * Seeder MasterDataSeeder mengisi data dasar destinasi.
 */
class MasterDataSeeder extends Seeder
{
    /**
     * Jalankan seeder master data.
     */
    public function run(): void
    {
        $this->seedAttractions();
        $this->seedGlobalOperatingHours();
        $this->seedTicketTypes();
        $this->seedFacilities();
        $this->seedMerchants();
    }

    /**
     * Isi data atraksi utama.
     */
    protected function seedAttractions(): void
    {
        Attraction::query()->delete();

        Attraction::create([
            'name' => 'Perahu Bebek',
            'type' => 'WAHANA',
            'description' => 'Wahana perahu bebek untuk keluarga dengan pemandangan danau.',
            'base_price' => 15000,
        ]);

        Attraction::create([
            'name' => 'Spot Sunset Bukit Manduk',
            'type' => 'SPOT',
            'description' => 'Spot favorit menikmati matahari terbenam di Bukit Manduk.',
            'base_price' => null,
        ]);

        Attraction::create([
            'name' => 'Dermaga Utama',
            'type' => 'GENERAL',
            'description' => 'Dermaga utama sebagai pintu gerbang aktivitas wisata air.',
            'base_price' => null,
        ]);
    }

    /**
     * Isi jam operasional umum.
     */
    protected function seedGlobalOperatingHours(): void
    {
        OperatingHour::query()->delete();

        foreach (range(1, 5) as $day) {
            OperatingHour::create([
                'day_of_week' => $day,
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'is_closed' => false,
                'attraction_id' => null,
            ]);
        }

        foreach ([6, 0] as $day) {
            OperatingHour::create([
                'day_of_week' => $day,
                'open_time' => '07:00:00',
                'close_time' => '18:00:00',
                'is_closed' => false,
                'attraction_id' => null,
            ]);
        }
    }

    /**
     * Isi data jenis tiket.
     */
    protected function seedTicketTypes(): void
    {
        TicketType::query()->delete();

        TicketType::create([
            'name' => 'Tiket Masuk',
            'weekday_price' => 5000,
            'weekend_price' => 7000,
            'holiday_price' => 10000,
        ]);

        TicketType::create([
            'name' => 'Parkir Motor',
            'weekday_price' => 2000,
            'weekend_price' => 3000,
            'holiday_price' => 3000,
        ]);

        TicketType::create([
            'name' => 'Sewa Perahu Bebek (30 menit)',
            'weekday_price' => 15000,
            'weekend_price' => 20000,
            'holiday_price' => 20000,
        ]);
    }

    /**
     * Isi daftar fasilitas umum.
     */
    protected function seedFacilities(): void
    {
        Facility::query()->delete();

        $facilityNames = ['Mushola', 'Toilet', 'Tempat Sampah', 'Gazebo', 'Tempat Charger'];

        foreach ($facilityNames as $name) {
            Facility::create([
                'name' => $name,
                'description' => 'Fasilitas '.$name.' tersedia untuk pengunjung.',
            ]);
        }
    }

    /**
     * Isi data pedagang UMKM mitra.
     */
    protected function seedMerchants(): void
    {
        Merchant::query()->delete();

        $merchants = [
            ['name' => 'Warung Segar Manduk', 'category' => 'KULINER'],
            ['name' => 'Dapur Apung Mbak Rina', 'category' => 'KULINER'],
            ['name' => 'Sate Danau Pak Budi', 'category' => 'KULINER'],
            ['name' => 'Kopi Bukit Manduk', 'category' => 'KULINER'],
            ['name' => 'Kuliner Lesehan Dermaga', 'category' => 'KULINER'],
            ['name' => 'Souvenir Manduk Craft', 'category' => 'SOUVENIR'],
            ['name' => 'Oleh-oleh Bukit Ceria', 'category' => 'SOUVENIR'],
            ['name' => 'Manduk Gift Corner', 'category' => 'SOUVENIR'],
        ];

        $counter = 1;

        foreach ($merchants as $merchant) {
            Merchant::create([
                'name' => $merchant['name'],
                'category' => $merchant['category'],
                'location' => 'Zona UMKM #'.$counter,
                'whatsapp' => '6281234000'.str_pad((string) $counter, 2, '0', STR_PAD_LEFT),
                'phone' => null,
                'is_verified' => $counter <= 5,
            ]);

            $counter++;
        }
    }
}
