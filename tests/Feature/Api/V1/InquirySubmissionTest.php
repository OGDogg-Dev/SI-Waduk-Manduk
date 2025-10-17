<?php

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('menerima inquiry publik dan menerapkan rate limit', function () {
    $payload = [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'phone' => '08123456789',
        'type' => 'SARAN',
        'message' => 'Perlu lebih banyak papan petunjuk.',
    ];

    $response = $this->postJson('/api/v1/inquiries', $payload);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Budi')
        ->assertJsonPath('data.status', 'BARU');

    $this->assertDatabaseHas(Inquiry::class, [
        'name' => 'Budi',
        'type' => 'SARAN',
    ]);

    for ($i = 0; $i < 4; $i++) {
        $this->postJson('/api/v1/inquiries', array_merge($payload, [
            'email' => 'budi'.$i.'@example.com',
            'message' => 'Pesan tambahan '.$i,
        ]))->assertCreated();
    }

    $limited = $this->postJson('/api/v1/inquiries', array_merge($payload, [
        'email' => 'terbatas@example.com',
        'message' => 'Permintaan kena limit',
    ]));

    $limited->assertStatus(429);
});
