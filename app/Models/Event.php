<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Model Event menyimpan informasi acara wisata.
 */
class Event extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Kolom yang dapat diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_at',
        'end_at',
        'venue',
        'is_published',
    ];

    /**
     * Konversi atribut ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_published' => 'boolean',
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
