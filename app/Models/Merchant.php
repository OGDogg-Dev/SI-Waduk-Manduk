<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Model Merchant mewakili mitra UMKM.
 */
class Merchant extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Kolom yang boleh diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'phone',
        'whatsapp',
        'location',
        'is_verified',
    ];

    /**
     * Konversi atribut otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Daftarkan koleksi media.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('public');
        $this->addMediaCollection('cover')->singleFile()->useDisk('public');
    }
}
