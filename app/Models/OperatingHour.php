<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model OperatingHour menyimpan jadwal operasional.
 */
class OperatingHour extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
        'attraction_id',
    ];

    /**
     * Konversi tipe otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day_of_week' => 'integer',
        'is_closed' => 'boolean',
    ];

    /**
     * Relasi ke attraction terkait.
     */
    public function attraction(): BelongsTo
    {
        return $this->belongsTo(Attraction::class);
    }
}
