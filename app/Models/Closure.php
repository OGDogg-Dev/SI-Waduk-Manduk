<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Closure mencatat penutupan sementara lokasi.
 */
class Closure extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reason',
        'start_at',
        'end_at',
        'attraction_id',
    ];

    /**
     * Konversi atribut otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'attraction_id' => 'integer',
    ];

    /**
     * Relasi ke attraction terkait.
     */
    public function attraction(): BelongsTo
    {
        return $this->belongsTo(Attraction::class);
    }
}
