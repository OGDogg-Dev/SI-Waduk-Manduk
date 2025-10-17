<?php

use App\Models\Attraction;
use App\Models\Closure;
use App\Models\OperatingHour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('menampilkan struktur status operasional hari ini', function () {
    Carbon::setTestNow(Carbon::create(2025, 1, 6, 9, 0, 0));

    $attraction = Attraction::factory()->create(['is_active' => true]);

    OperatingHour::factory()->create([
        'day_of_week' => Carbon::now()->dayOfWeek,
        'open_time' => '08:00:00',
        'close_time' => '17:00:00',
        'attraction_id' => $attraction->id,
    ]);

    Closure::factory()->create([
        'attraction_id' => $attraction->id,
        'start_at' => Carbon::now()->subHour(),
        'end_at' => Carbon::now()->addHours(2),
        'reason' => 'Perawatan ringan',
    ]);

    $response = $this->getJson('/api/v1/status-today?attraction_slug='.$attraction->slug);

    $response->assertOk()
        ->assertJsonStructure([
            'open_now',
            'open_time',
            'close_time',
            'closures_today' => [
                [
                    'id',
                    'reason',
                    'start_at',
                    'end_at',
                    'attraction_name',
                ],
            ],
        ])
        ->assertJsonPath('open_now', false);

    Carbon::setTestNow();
});
