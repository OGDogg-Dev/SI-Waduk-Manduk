<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Setting menyimpan konfigurasi kunci-nilai.
 */
class Setting extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Konversi atribut otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'array',
    ];
}
