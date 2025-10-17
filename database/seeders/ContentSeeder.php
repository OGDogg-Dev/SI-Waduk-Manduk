<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attraction;
use App\Models\Closure as AttractionClosure;
use App\Models\Event;
use App\Models\Inquiry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Seeder ContentSeeder mengisi data konten dinamis.
 */
class ContentSeeder extends Seeder
{
    /**
     * Jalankan seeder konten.
     */
    public function run(): void
    {
        $this->seedEvents();
        $this->seedAnnouncements();
        $this->seedInquiries();
        $this->seedClosures();
    }

    /**
     * Seed acara unggulan.
     */
    protected function seedEvents(): void
    {
        Event::query()->delete();

        Event::create([
            'title' => 'Festival Mancing Manduk',
            'description' => 'Festival tahunan lomba mancing dengan hadiah menarik.',
            'start_at' => Carbon::create(2025, 11, 10, 6, 0, 0),
            'end_at' => null,
            'venue' => 'Dermaga Utama',
            'is_published' => true,
        ]);
    }

    /**
     * Seed pengumuman penting.
     */
    protected function seedAnnouncements(): void
    {
        Announcement::query()->delete();

        Announcement::create([
            'title' => 'Pengumuman Cuaca',
            'content' => 'Mohon waspada cuaca berawan dengan potensi hujan ringan.',
            'severity' => 'WARNING',
            'published_at' => Carbon::now(),
        ]);

        Announcement::create([
            'title' => 'Area Bukit Manduk Ditutup Sementara',
            'content' => 'Penutupan sementara untuk perbaikan akses jalur wisata.',
            'severity' => 'ALERT',
            'published_at' => Carbon::now(),
            'expired_at' => Carbon::now()->addDays(3),
        ]);
    }

    /**
     * Seed contoh pertanyaan pengunjung.
     */
    protected function seedInquiries(): void
    {
        Inquiry::query()->delete();

        Inquiry::create([
            'name' => 'Andi',
            'email' => 'andi@example.com',
            'type' => 'PERTANYAAN',
            'message' => 'Apakah tersedia penyewaan pelampung untuk anak?',
        ]);

        Inquiry::create([
            'name' => 'Siti',
            'phone' => '081234567890',
            'type' => 'SARAN',
            'message' => 'Saran penambahan kursi tunggu di area dermaga.',
        ]);

        Inquiry::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'type' => 'PENGADUAN',
            'message' => 'Perahu bebek nomor 3 perlu diperiksa kembali.',
        ]);
    }

    /**
     * Seed data penutupan lokasi.
     */
    protected function seedClosures(): void
    {
        AttractionClosure::query()->delete();

        $spotSunset = Attraction::where('name', 'Spot Sunset Bukit Manduk')->first();

        if (! $spotSunset) {
            return;
        }

        AttractionClosure::create([
            'reason' => 'Perbaikan jalur trekking menuju spot sunset.',
            'start_at' => Carbon::now()->addDays(1)->setTime(7, 0),
            'end_at' => Carbon::now()->addDays(3)->setTime(18, 0),
            'attraction_id' => $spotSunset->id,
        ]);
    }
}
