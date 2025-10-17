<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Model Facility menggambarkan fasilitas yang tersedia.
 */
class Facility extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Kolom yang dapat diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'is_available',
    ];

    /**
     * Konversi atribut otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_available' => 'boolean',
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
