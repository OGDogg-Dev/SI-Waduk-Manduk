<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Model Attraction merepresentasikan daya tarik wisata.
 */
class Attraction extends Model implements HasMedia
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
        'type',
        'description',
        'base_price',
        'is_active',
        'latitude',
        'longitude',
    ];

    /**
     * Konversi atribut otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Relasi ke jam operasional.
     */
    public function operatingHours(): HasMany
    {
        return $this->hasMany(OperatingHour::class);
    }

    /**
     * Relasi ke data penutupan.
     */
    public function closures(): HasMany
    {
        return $this->hasMany(Closure::class);
    }

    /**
     * Daftarkan koleksi media.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('public');
        $this->addMediaCollection('cover')->singleFile()->useDisk('public');
    }
}
