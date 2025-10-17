<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Model Announcement menyimpan pengumuman penting.
 */
class Announcement extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Kolom yang boleh diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'severity',
        'published_at',
        'expired_at',
    ];

    /**
     * Konversi atribut otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
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
