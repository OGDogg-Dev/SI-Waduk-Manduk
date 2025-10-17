<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TicketType menyimpan informasi jenis tiket.
 */
class TicketType extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'weekday_price',
        'weekend_price',
        'holiday_price',
        'is_active',
    ];

    /**
     * Konversi tipe kolom.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weekday_price' => 'decimal:2',
        'weekend_price' => 'decimal:2',
        'holiday_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
